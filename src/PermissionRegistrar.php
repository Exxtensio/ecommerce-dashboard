<?php

namespace Exxtensio\EcommerceDashboard;

use DateInterval;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Database\Eloquent\Collection;

class PermissionRegistrar
{
    protected Repository $cache;

    protected CacheManager $cacheManager;

    protected string $permissionClass;

    protected string $roleClass;

    protected $permissions;

    public string $pivotRole;

    public string $pivotPermission;

    public int|DateInterval $cacheExpirationTime;

    public string $cacheKey;

    private array $cachedRoles = [];

    private array $alias = [];

    private array $except = [];

    /**
     * PermissionRegistrar constructor.
     */
    public function __construct(CacheManager $cacheManager)
    {
        $this->permissionClass = Models\Permission::class;
        $this->roleClass = Models\Role::class;

        $this->cacheManager = $cacheManager;
        $this->initializeCache();
    }

    public function initializeCache(): void
    {
        $this->cacheExpirationTime = DateInterval::createFromDateString('24 hours');
        $this->cacheKey = 'sellexx.permission.cache';
        $this->pivotRole = 'role_id';
        $this->pivotPermission = 'permission_id';

        $this->cache = $this->getCacheStoreFromConfig();
    }

    protected function getCacheStoreFromConfig(): Repository
    {
        return $this->cacheManager->store();
    }

    /**
     * Register the permission check method on the gate.
     * We resolve the Gate fresh here, for benefit of long-running instances.
     */
    public function registerPermissions(Gate $gate): bool
    {
        $gate->before(function (Authorizable $user, string $ability, array &$args = []) {
            if (is_string($args[0] ?? null) && !class_exists($args[0])) {
                $guard = array_shift($args);
            }
            if (method_exists($user, 'checkPermissionTo')) {
                return $user->checkPermissionTo($ability, $guard ?? null) ?: null;
            }
        });

        return true;
    }

    /**
     * Flush the cache.
     */
    public function forgetCachedPermissions(): bool
    {
        $this->permissions = null;

        return $this->cache->forget($this->cacheKey);
    }

    /**
     * Clear already-loaded permissions collection.
     * This is only intended to be called by the PermissionServiceProvider on boot,
     * so that long-running instances like Octane or Swoole don't keep old data in memory.
     */
    public function clearPermissionsCollection(): void
    {
        $this->permissions = null;
    }

    /**
     * Load permissions from cache
     * And turns permissions array into a \Illuminate\Database\Eloquent\Collection
     */
    private function loadPermissions(): void
    {
        if ($this->permissions) return;

        $this->permissions = $this->cache->remember(
            $this->cacheKey,
            $this->cacheExpirationTime,
            fn() => $this->getSerializedPermissionsForCache()
        );

        $this->alias = $this->permissions['alias'];

        $this->hydrateRolesCache();

        $this->permissions = $this->getHydratedPermissionCollection();

        $this->cachedRoles = $this->alias = $this->except = [];
    }

    /**
     * Get the permissions based on the passed params.
     */
    public function getPermissions(array $params = [], bool $onlyOne = false): Collection
    {
        $this->loadPermissions();

        $method = $onlyOne ? 'first' : 'filter';

        $permissions = $this->permissions->$method(static function ($permission) use ($params) {
            foreach ($params as $attr => $value) {
                if ($permission->getAttribute($attr) != $value) return false;
            }

            return true;
        });

        if ($onlyOne)
            $permissions = new Collection($permissions ? [$permissions] : []);

        return $permissions;
    }

    public function getPermissionClass(): string
    {
        return $this->permissionClass;
    }

    public function setPermissionClass($permissionClass): static
    {
        $this->permissionClass = $permissionClass;
        app()->bind(Contracts\Permission::class, $permissionClass);

        return $this;
    }

    public function getRoleClass(): string
    {
        return $this->roleClass;
    }

    public function setRoleClass($roleClass): static
    {
        $this->roleClass = $roleClass;
        app()->bind(Contracts\Role::class, $roleClass);

        return $this;
    }

    public function getCacheRepository(): Repository
    {
        return $this->cache;
    }

    public function getCacheStore(): Store
    {
        return $this->cache->getStore();
    }

    protected function getPermissionsWithRoles(): Collection
    {
        return $this->permissionClass::select()->with('roles')->get();
    }

    /**
     * Changes array keys with alias
     */
    private function aliasedArray($model): array
    {
        return collect(is_array($model) ? $model : $model->getAttributes())
            ->except($this->except)
            ->keyBy(fn($value, $key) => $this->alias[$key] ?? $key)
            ->all();
    }

    /**
     * Array for cache alias
     */
    private function aliasModelFields($newKeys = []): void
    {
        $i = 0;
        $alphas = !count($this->alias) ? range('a', 'h') : range('j', 'p');

        foreach (array_keys($newKeys->getAttributes()) as $value) {
            if (!isset($this->alias[$value])) {
                $this->alias[$value] = $alphas[$i++] ?? $value;
            }
        }

        $this->alias = array_diff_key(
            $this->alias,
            array_flip($this->except)
        );
    }

    /*
     * Make the cache smaller using an array with only required fields
     */
    private function getSerializedPermissionsForCache(): array
    {
        $this->except = ['created_at', 'updated_at', 'deleted_at'];

        $permissions = $this->getPermissionsWithRoles()
            ->map(function ($permission) {
                if (!$this->alias)
                    $this->aliasModelFields($permission);

                return $this->aliasedArray($permission) + $this->getSerializedRoleRelation($permission);
            })->all();
        $roles = array_values($this->cachedRoles);
        $this->cachedRoles = [];

        return ['alias' => array_flip($this->alias)] + compact('permissions', 'roles');
    }

    private function getSerializedRoleRelation($permission): array
    {
        if (!$permission->roles->count()) {
            return [];
        }

        if (!isset($this->alias['roles'])) {
            $this->alias['roles'] = 'r';
            $this->aliasModelFields($permission->roles[0]);
        }

        return [
            'r' => $permission->roles->map(function ($role) {
                if (!isset($this->cachedRoles[$role->getKey()])) {
                    $this->cachedRoles[$role->getKey()] = $this->aliasedArray($role);
                }

                return $role->getKey();
            })->all(),
        ];
    }

    private function getHydratedPermissionCollection(): Collection
    {
        $permissionInstance = (new ($this->getPermissionClass())())->newInstance([], true);

        return Collection::make(array_map(
            fn($item) => (clone $permissionInstance)
                ->setRawAttributes($this->aliasedArray(array_diff_key($item, ['r' => 0])), true)
                ->setRelation('roles', $this->getHydratedRoleCollection($item['r'] ?? [])),
            $this->permissions['permissions']
        ));
    }

    private function getHydratedRoleCollection(array $roles): Collection
    {
        return Collection::make(array_values(
            array_intersect_key($this->cachedRoles, array_flip($roles))
        ));
    }

    private function hydrateRolesCache(): void
    {
        $roleInstance = (new ($this->getRoleClass())())->newInstance([], true);

        array_map(function ($item) use ($roleInstance) {
            $role = (clone $roleInstance)
                ->setRawAttributes($this->aliasedArray($item), true);
            $this->cachedRoles[$role->getKey()] = $role;
        }, $this->permissions['roles']);

        $this->permissions['roles'] = [];
    }

    public static function isUid($value): bool
    {
        if (!is_string($value) || empty(trim($value))) {
            return false;
        }

        // check if is UUID/GUID
        $uid = preg_match('/^[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}$/iD', $value) > 0;
        if ($uid) {
            return true;
        }

        // check if is ULID
        $ulid = strlen($value) == 26 && strspn($value, '0123456789ABCDEFGHJKMNPQRSTVWXYZabcdefghjkmnpqrstvwxyz') == 26 && $value[0] <= '7';
        if ($ulid) {
            return true;
        }

        return false;
    }
}
