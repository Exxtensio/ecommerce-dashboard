<?php

namespace Exxtensio\EcommerceDashboard\Exceptions;

use Illuminate\Support\Collection;
use InvalidArgumentException;

class GuardDoesNotMatch extends InvalidArgumentException
{
    public static function create(string $givenGuard, Collection $expectedGuards): static
    {
        $guards = $expectedGuards->implode(', ');
        return new static(__('The given role or permission should use guard `:guards` instead of `:givenGuard`.', ['guards' => $guards, 'givenGuard' => $givenGuard]));
    }
}
