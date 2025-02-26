<?php

namespace Exxtensio\EcommerceDashboard\Exceptions;

use Exception;

class CouldNotLogChanges extends Exception
{
    public static function invalidAttribute($attribute): self
    {
        return new static(__('Cannot log attribute `:attribute`. Can only log attributes of a model or a directly related model.', ['attribute' => $attribute]));
    }
}
