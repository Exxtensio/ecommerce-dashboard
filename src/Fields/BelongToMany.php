<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits\Related;

class BelongToMany extends Field
{
    use Related;

    public string $component = 'belong-to-many-field';
    public string $foreignKey;
    public ?string $groupRelation = null;
    public ?string $groupAttribute = null;
    public bool $relatable = true;

    public function groupBy(string $attribute, ?string $relation = null): static
    {
        $this->groupRelation = $relation;
        $this->groupAttribute = $attribute;

        return $this;
    }
}
