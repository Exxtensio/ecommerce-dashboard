<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits\Related;

class CartItems extends Field
{
    use Related;

    public string $component = 'cart-items-field';
}
