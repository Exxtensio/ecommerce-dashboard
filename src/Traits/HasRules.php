<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait HasRules
{
    public array $rules = [];
    public array $creationRules = [];
    public array $updateRules = [];

    public function rules(array $rules): static
    {
        $this->rules = $rules;

        return $this;
    }

    public function creationRules(array $rules): static
    {
        $this->creationRules = $rules;

        return $this;
    }

    public function updateRules(array $rules): static
    {
        $this->updateRules = $rules;

        return $this;
    }
}
