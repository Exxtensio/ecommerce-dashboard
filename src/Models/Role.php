<?php

namespace Exxtensio\EcommerceDashboard\Models;

use BackedEnum;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Exxtensio\EcommerceDashboard\Contracts\Permission;
use Exxtensio\EcommerceDashboard\Contracts\Role as RoleContract;
use Exxtensio\EcommerceDashboard\Database\Factories\RoleFactory;
use Exxtensio\EcommerceDashboard\Exceptions;
use Exxtensio\EcommerceDashboard\Guard;
use Exxtensio\EcommerceDashboard\PermissionRegistrar;
use Exxtensio\EcommerceDashboard\Traits;
use ReflectionException;

/**
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class Role extends Model implements RoleContract
{
    use HasUlids,
        HasFactory,
        Traits\HasPermissions,
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

    protected static function newFactory(): Factory|RoleFactory|null
    {
        return RoleFactory::new();
    }

    /**
     * @param array $attributes
     * @return RoleContract|Role
     * @throws ReflectionException
     */
    public static function create(array $attributes = []): RoleContract|Role
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $params = ['name' => $attributes['name'], 'guard_name' => $attributes['guard_name']];
        if (static::findByParam($params))
            throw Exceptions\RoleAlreadyExists::create($attributes['name'], $attributes['guard_name']);

        return static::query()->create($attributes);
    }

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            \Exxtensio\EcommerceDashboard\Models\Permission::class,
            'role_has_permissions',
            app(PermissionRegistrar::class)->pivotRole,
            app(PermissionRegistrar::class)->pivotPermission
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
            'model_has_roles',
            app(PermissionRegistrar::class)->pivotRole,
            'model_id'
        );
    }

    /**
     * @param string $name
     * @param string|null $guardName
     * @return RoleContract
     * @throws ReflectionException
     */
    public static function findByName(string $name, ?string $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::findByParam(['name' => $name, 'guard_name' => $guardName]);
        if (!$role)
            throw Exceptions\RoleDoesNotExist::named($name, $guardName);

        return $role;
    }

    /**
     * @param int|string $id
     * @param string|null $guardName
     * @return RoleContract
     * @throws ReflectionException
     */
    public static function findById(int|string $id, ?string $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::findByParam([(new static)->getKeyName() => $id, 'guard_name' => $guardName]);
        if (!$role)
            throw Exceptions\RoleDoesNotExist::withId($id, $guardName);

        return $role;
    }

    /**
     * @param string $name
     * @param string|null $guardName
     * @return RoleContract
     * @throws ReflectionException
     */
    public static function findOrCreate(string $name, ?string $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::findByParam(['name' => $name, 'guard_name' => $guardName]);
        if (!$role)
            return static::query()->create([
                'name' => $name,
                'guard_name' => $guardName
            ]);

        return $role;
    }

    /**
     * @param array $params
     * @return RoleContract|null
     */
    protected static function findByParam(array $params = []): ?RoleContract
    {
        $query = static::query();

        foreach ($params as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }

    /**
     * @param BackedEnum|int|Permission|string $permission
     * @param string|null $guardName
     * @return bool
     * @throws ReflectionException
     */
    public function hasPermissionTo(BackedEnum|int|Permission|string $permission, ?string $guardName = null): bool
    {
        $permission = $this->filterPermission($permission, $guardName);

        if (!$this->getGuardNames()->contains($permission->guard_name))
            throw Exceptions\GuardDoesNotMatch::create($permission->guard_name, $guardName ? collect([$guardName]) : $this->getGuardNames());

        return $this->permissions->contains($permission->getKeyName(), $permission->getKey());
    }
}
