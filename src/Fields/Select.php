<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits;

class Select extends Field
{
    use Traits\HasPlaceholder,
        Traits\HasDefault,
        Traits\HasDepend,
        Traits\Nullable,
        Traits\Searchable;

    public string $component = 'select-field';
    public array $options = [];
    public ?string $selected = null;

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
