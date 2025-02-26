<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits\Related;

class Permissions extends Field
{
    use Related;

    public string $component = 'permissions-field';
    public string $foreignKey;
    public bool $relatable = true;
}
