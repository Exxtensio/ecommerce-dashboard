<?php

namespace Exxtensio\EcommerceDashboard\Exceptions;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthorizedException extends HttpException
{
    private array $requiredRoles = [];
    private array $requiredPermissions = [];

    public static function forRoles(array $roles): self
    {
        $exception = new static(
            403,
            'User does not have the right roles.',
            null,
            []
        );
        $exception->requiredRoles = $roles;

        return $exception;
    }

    public static function forPermissions(array $permissions): self
    {
        $exception = new static(
            403,
            'User does not have the right permissions.',
            null,
            []
        );
        $exception->requiredPermissions = $permissions;

        return $exception;
    }

    public static function forRolesOrPermissions(array $rolesOrPermissions): self
    {
        $exception = new static(
            403,
            'User does not have any of the necessary access rights.',
            null,
            []
        );
        $exception->requiredPermissions = $rolesOrPermissions;

        return $exception;
    }

    public static function missingTraitHasRoles(Authorizable $user): self
    {
        $class = get_class($user);

        return new static(
            403,
            "Authorizable class `$class` must use Exxtensio\EcommerceDashboard\Traits\HasRoles trait.",
            null,
            []
        );
    }

    public static function notLoggedIn(): self
    {
        return new static(
            403,
            'User is not logged in.',
            null,
            []
        );
    }

    public function getRequiredRoles(): array
    {
        return $this->requiredRoles;
    }

    public function getRequiredPermissions(): array
    {
        return $this->requiredPermissions;
    }
}
