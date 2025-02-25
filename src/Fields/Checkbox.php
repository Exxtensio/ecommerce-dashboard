<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits\HasBoolDefault;

class Checkbox extends Field
{
    use HasBoolDefault;

    public string $component = 'checkbox-field';
}
