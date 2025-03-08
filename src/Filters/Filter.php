<?php

namespace Exxtensio\EcommerceDashboard\Filters;

use Exxtensio\EcommerceDashboard\Traits;

abstract class Filter
{
    use Traits\Makeable,
        Traits\HasHelpText;

    public string $name;
    public string $relation;
    public ?string $attribute = null;
    public mixed $value = null;

    protected function __construct(string $name, string $attribute, ?string $relation = null)
    {
        $this->name = $name;
        $this->attribute = $attribute;
        $this->relation = !$relation ? $attribute : $relation;

        $this->setPlaceholder($name);
    }

    private function setPlaceholder($name): void
    {
        if (property_exists($this, 'placeholder'))
            $this->placeholder = $name;
    }
}
