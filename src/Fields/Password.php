<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits;

class Password extends Field
{
    use Traits\HasCopyable,
        Traits\HasPlaceholder,
        Traits\HasTruncated;

    public string $component = 'password-field';
    public bool $showOnIndex = false;
    public bool $showOnColumns = false;
    public bool $showOnDetail = false;
}
