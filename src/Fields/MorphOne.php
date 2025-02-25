<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits;

class MorphOne extends Field
{
    use Traits\Related,
        Traits\Nullable,
        Traits\Searchable;

    public string $component = 'morph-one-field';
    public string $foreignKey;
    public array $options = [];
    public bool $relatable = true;
}
