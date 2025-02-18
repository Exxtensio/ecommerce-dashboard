<?php

namespace Exxtensio\EcommerceDashboard\Exceptions;

use InvalidArgumentException;

class RoleDoesNotExist extends InvalidArgumentException
{
    public static function named(string $roleName, ?string $guardName): static
    {
        return new static("There is no role named `$roleName` for guard `$guardName`.");
    }

    public static function withId(int|string $roleId, ?string $guardName): static
    {
        return new static("There is no role with ID `$roleId` for guard `$guardName`.");
    }
}
