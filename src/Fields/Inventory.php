<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits\Related;

class Inventory extends Field
{
    use Related;

    public string $component = 'inventory-field';
}
