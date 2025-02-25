<?php

namespace Exxtensio\EcommerceDashboard\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Exxtensio\EcommerceDashboard\Exceptions;
use Exxtensio\EcommerceDashboard\Models;

/**
 * @property int|string $id
 * @property string $name
 * @property string $group
 * @property string|null $guard_name
 * @mixin Models\Permission
 */
interface Permission
{
    public function roles(): BelongsToMany;

    /** @throws Exceptions\PermissionDoesNotExist */
    public static function findByName(string $name, ?string $guardName): self;

    /** @throws Exceptions\PermissionDoesNotExist */
    public static function findById(int|string $id, ?string $guardName): self;

    public static function findOrCreate(string $name, string $group, ?string $guardName): self;
}
