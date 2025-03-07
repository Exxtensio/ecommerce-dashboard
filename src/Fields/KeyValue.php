<?php

namespace Exxtensio\EcommerceDashboard\Fields;

use Exception;

class KeyValue extends Field
{
    public string $component = 'key-value-field';
    public string $keyLabel = 'Key';
    public string $valueLabel = 'Value';
    public string $actionText = 'Add Row';
    public bool $canAddRow = true;
    public bool $canDeleteRow = true;

    public function keyLabel($label): static
    {
        $this->keyLabel = $label;

        return $this;
    }

    public function valueLabel($label): static
    {
        $this->valueLabel = $label;

        return $this;
    }

    public function actionText($label): static
    {
        $this->actionText = $label;

        return $this;
    }

    public function disableAddingRows(): static
    {
        $this->canAddRow = false;

        return $this;
    }

    public function disableDeletingRows(): static
    {
        $this->canDeleteRow = false;

        return $this;
    }

    /** @throws Exception */
    public function sortable(bool $value = true): static
    {
        throw new Exception('Sortable is not supported in the KeyValue field.');
    }
}
