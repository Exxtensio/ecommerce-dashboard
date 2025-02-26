<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Exxtensio\EcommerceDashboard\Contracts\Permission as PermissionContract;
use Exxtensio\EcommerceDashboard\Exceptions;
use Exxtensio\EcommerceDashboard\Guard;
use Exxtensio\EcommerceDashboard\PermissionRegistrar;
use Exxtensio\EcommerceDashboard\Traits;
use ReflectionException;

/**
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class Permission extends Model implements PermissionContract
{
    use HasUlids,
        Traits\HasRoles,
        Traits\HasActivity,
        Traits\RefreshesPermissionCache;

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

        parent::__construct($attributes);

        $this->guarded[] = $this->primaryKey;
        $this->table = parent::getTable();
    }

    /**
     * @param array $attributes
     * @return PermissionContract|Permission
     * @throws ReflectionException
     */
    public static function create(array $attributes = []): PermissionContract|Permission
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $permission = static::getPermission([
            'name' => $attributes['name'],
            'group' => $attributes['group'],
            'guard_name' => $attributes['guard_name']
        ]);

        if ($permission)
            throw Exceptions\PermissionAlreadyExists::create($attributes['name'], $attributes['guard_name']);

        return static::query()->create($attributes);
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            \Exxtensio\EcommerceDashboard\Models\Role::class,
            'role_has_permissions',
            app(PermissionRegistrar::class)->pivotPermission,
            app(PermissionRegistrar::class)->pivotRole
        );
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name'] ?? config('auth.defaults.guard')),
            'model',
            'model_has_permissions',
            app(PermissionRegistrar::class)->pivotPermission,
            'model_id'
        );
    }

    /**
     * @param string $name
     * @param string|null $guardName
     * @return PermissionContract
     * @throws ReflectionException
     */
    public static function findByName(string $name, ?string $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermission(['name' => $name, 'guard_name' => $guardName]);
        if (!$permission)
            throw Exceptions\PermissionDoesNotExist::create($name, $guardName);

        return $permission;
    }

    /**
     * @param int|string $id
     * @param string|null $guardName
     * @return PermissionContract
     * @throws ReflectionException
     */
    public static function findById(int|string $id, ?string $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermission([(new static)->getKeyName() => $id, 'guard_name' => $guardName]);

        if (!$permission)
            throw Exceptions\PermissionDoesNotExist::withId($id, $guardName);

        return $permission;
    }

    /**
     * @param string $name
     * @param string $group
     * @param string|null $guardName
     * @return PermissionContract
     * @throws ReflectionException
     */
    public static function findOrCreate(string $name, string $group, ?string $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermission(['name' => $name, 'guard_name' => $guardName]);

        if (!$permission)
            return static::query()->create(['name' => $name, 'group' => $group, 'guard_name' => $guardName]);

        return $permission;
    }

    /**
     * @param array $params
     * @param bool $onlyOne
     * @return Collection
     */
    protected static function getPermissions(array $params = [], bool $onlyOne = false): Collection
    {
        return app(PermissionRegistrar::class)
            ->setPermissionClass(static::class)
            ->getPermissions($params, $onlyOne);
    }

    /**
     * @param array $params
     * @return PermissionContract|null
     */
    protected static function getPermission(array $params = []): ?PermissionContract
    {
        return static::getPermissions($params, true)->first();
    }
}
