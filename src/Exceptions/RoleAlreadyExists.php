<?php

namespace Exxtensio\EcommerceDashboard\Exceptions;

use InvalidArgumentException;

class RoleAlreadyExists extends InvalidArgumentException
{
    public static function create(string $roleName, string $guardName): static
    {
        return new static('A role `:roleName` already exists for guard `:guardName`.', ['roleName' => $roleName, 'guardName' => $guardName]);
    }
}
