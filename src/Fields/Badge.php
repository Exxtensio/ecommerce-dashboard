<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits;

class Badge extends Field
{
    use Traits\HasPlaceholder,
        Traits\HasDefault;

    public string $component = 'badge-field';
    public array $badges = [];
    public array $options = [];
    public ?string $selected = null;
    public ?string $badgeType = null;

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

    public function map(array $array): static
    {
        $this->badges = $array;

        return $this;
    }
}
