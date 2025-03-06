<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits;

class BelongTo extends Field
{
    use Traits\Related,
        Traits\Nullable,
        Traits\Searchable;

    public string $component = 'belong-to-field';
    public string $foreignKey;
    public array $options = [];
    public array $exclude = [];
    public bool $relatable = true;

    public function exclude(array $array): static
    {
        $this->exclude = $array;

        return $this;
    }
}
