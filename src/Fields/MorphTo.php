<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits\Related;

class MorphTo extends Field
{
    use Related;

    public string $component = 'morph-to-field';
    public string $foreignKey;
    public string $morphType;
    public ?object $morphValue;
    public bool $relatable = true;
}
