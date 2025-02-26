<?php

namespace Exxtensio\EcommerceDashboard\Filters;

use Exxtensio\EcommerceDashboard\Traits;

class Select extends Filter
{
    use Traits\Searchable,
        Traits\Separatable;

    public string $component = 'select-filter';
    public array $options = [];

    public function options(array $options = []): static
    {
        $collection = collect($options);
        $this->options = !array_is_list($options)
            ? $collection
                ->map(fn($value, $key) => ['name' => $value, 'value' => $key])
                ->values()
                ->toArray()
            : $collection
                ->map(fn($key) => ['name' => $key, 'value' => $key])
                ->values()
                ->toArray();

        return $this;
    }
}
