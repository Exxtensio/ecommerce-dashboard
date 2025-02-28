<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits;

class Slug extends Field
{
    use Traits\HasCopyable,
        Traits\HasPlaceholder,
        Traits\HasTruncated;

    public string $component = 'slug-field';
    public ?string $from = null;
    public bool $readonly = true;

    public function from(string $attribute): static
    {
        $this->from = $attribute;

        return $this;
    }
}
