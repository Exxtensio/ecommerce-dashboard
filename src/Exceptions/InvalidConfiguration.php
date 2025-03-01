<?php

namespace Exxtensio\EcommerceDashboard\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Exxtensio\EcommerceDashboard\Contracts\Activity;

class InvalidConfiguration extends Exception
{
    public static function modelIsNotValid(string $className): self
    {
        return new static(__('The given model class `:className` does not implement `:activityClass` or it does not extend `:modelClass`.', ['className' => $className, 'activityClass' => Activity::class, 'modelClass' => Model::class]));
    }
}
