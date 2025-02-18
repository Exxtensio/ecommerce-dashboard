<?php

namespace Exxtensio\EcommerceDashboard\Exceptions;

use Illuminate\Support\Collection;
use InvalidArgumentException;

class GuardDoesNotMatch extends InvalidArgumentException
{
    public static function create(string $givenGuard, Collection $expectedGuards): static
    {
        return new static("The given role or permission should use guard `{$expectedGuards->implode(', ')}` instead of `$givenGuard`.");
    }
}
