<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits\Related;

class OrderItems extends Field
{
    use Related;

    public string $component = 'order-items-field';
}
