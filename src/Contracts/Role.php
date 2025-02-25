<?php

namespace Exxtensio\EcommerceDashboard\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Exxtensio\EcommerceDashboard\Exceptions;
use Exxtensio\EcommerceDashboard\Models;
use BackedEnum;

/**
 * @property int|string $id
 * @property string $name
 * @property string|null $guard_name
 * @mixin Models\Role
 */
interface Role
{
    public function permissions(): BelongsToMany;

    /** @throws Exceptions\RoleDoesNotExist */
    public static function findByName(string $name, ?string $guardName): self;

    /** @throws Exceptions\RoleDoesNotExist */
    public static function findById(int|string $id, ?string $guardName): self;

    public static function findOrCreate(string $name, ?string $guardName): self;

    public function hasPermissionTo(BackedEnum|int|Permission|string $permission, ?string $guardName): bool;
}
