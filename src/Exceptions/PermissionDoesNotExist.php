<?php

namespace Exxtensio\EcommerceDashboard\Exceptions;

use InvalidArgumentException;

class PermissionDoesNotExist extends InvalidArgumentException
{
    public static function create(string $permissionName, ?string $guardName): static
    {
        return new static(__('There is no permission named `:permissionName` for guard `:guardName`.', ['permissionName' => $permissionName, 'guardName' => $guardName]));
    }

    public static function withId(int|string $permissionId, ?string $guardName): static
    {
        return new static(__('There is no permission with ID `:permissionId` for guard `:guardName`.', ['permissionId' => $permissionId, 'guardName' => $guardName]));
    }
}
