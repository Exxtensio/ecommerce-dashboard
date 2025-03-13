<?php

namespace Exxtensio\EcommerceDashboard\Exceptions;

use InvalidArgumentException;

class PermissionAlreadyExists extends InvalidArgumentException
{
    public static function create(string $permissionName, string $guardName): static
    {
        return new static(__('A `:permissionName` permission already exists for guard `:guardName`.', ['permissionName' => $permissionName, 'guardName' => $guardName]));
    }
}
