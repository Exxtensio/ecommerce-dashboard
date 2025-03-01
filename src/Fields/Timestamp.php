<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exxtensio\EcommerceDashboard\Traits;

class Timestamp extends Field
{
    use Traits\HasPlaceholder;

    public string $component = 'timestamp-field';
    public bool $showOnUpdate = false;
    public bool $showOnCreation = false;
    public string $displayFormat = 'Y-m-d H:i:s';

    public function diffForHumans(): Timestamp
    {
        if(now()->subDays(7) < $this->value)
            $this->displayFormat = 'diffForHumans';

        return $this;
    }

    public function format(string $format = 'Y-m-d H:i:s'): Timestamp
    {
        $this->displayFormat = $format;

        return $this;
    }
}
