<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait Readable
{
    public bool $readonly = false;

    public function readonly(bool $bool = true): static
    {
        $this->readonly = $bool;

        return $this;
    }
}
