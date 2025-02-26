<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exception;
use Exxtensio\EcommerceDashboard\Traits\Nullable;

class Number extends Field
{
    use Nullable;

    public string $component = 'number-field';
    public int $places = 0;
    public int|float $step = 1;
    public null|int|float $default = null;

    public int|float $min = 1;
    public int|float $max = 99;

    public function min(int|float $value): static
    {
        $this->min = $value;

        return $this;
    }

    public function max(int|float $value): static
    {
        $this->max = $value;

        return $this;
    }

    /** @throws Exception */
    public function places(int $places): static
    {
        if ($places < 0)
            throw new Exception(__('Places must not be negative in the Number field.'));

        $this->places = $places;

        return $this;
    }

    public function step(int|float $value): static
    {
        $this->step = $value;

        return $this;
    }

    public function default(int|float $value): static
    {
        $this->default = $value;

        return $this;
    }

    public function resolved($relations): void
    {
        parent::resolved($relations);

        if(!$this->places) {
            $this->default = (int)$this->default;
            $this->min = (int)$this->min;
            $this->max = (int)$this->max;
        } else {
            $this->default = $this->default ? number_format($this->default, $this->places, '.', '') : null;
            $this->min = number_format($this->min, $this->places, '.', '');
            $this->max = number_format($this->max, $this->places, '.', '');
        }
    }
}
