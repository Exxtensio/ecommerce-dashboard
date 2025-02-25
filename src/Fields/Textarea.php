<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits;

class Textarea extends Field
{
    use Traits\HasPlaceholder;

    public string $component = 'textarea-field';
    public int $rows = 4;
    public int $limit = 25;

    public function __construct(string $name, string $attribute, mixed $value = null)
    {
        parent::__construct($name, $attribute, $value);
    }

    public function rows(int $rows): static
    {
        $this->rows = $rows;

        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = $limit;

        return $this;
    }
}
