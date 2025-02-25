<?php

namespace Exxtensio\EcommerceDashboard\Exceptions;

use Exception;

class CouldNotLogActivity extends Exception
{
    public static function couldNotDetermineUser($id): self
    {
        return new static(__('Could not determine a user with identifier `:id`.', ['id' => $id]));
    }
}
