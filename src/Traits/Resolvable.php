<?php

namespace Exxtensio\EcommerceDashboard\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait Resolvable
{
    public mixed $resolved = null;
    public mixed $resolvedForDisplay = null;
    public mixed $resolvedForUpdate = null;

    public function resolved($relations): void
    {
        if(
            property_exists($this, 'relationName') &&
            $this->relationName === $this->attribute &&
            !is_null($relations) &&
            !in_array($this->attribute, $relations)
        ) $this->hideFromIndex();

        if(!$this->value && $this->component !== 'checkbox-field') {
            $this->resolved = null;
        } else {
            $this->resolved = match ($this->component) {
                'checkbox-field' => (bool)$this->value,
                'timestamp-field' => Carbon::parse($this->value)->format('Y-m-d H:i:s'),
                'number-field' => !$this->places ? (int)$this->value : number_format($this->value, $this->places, '.', ''),
                'morph-to-field', 'morph-one-field', 'activities-field', 'permissions-field', 'belong-to-many-field', 'inventory-field', 'cart-items-field', 'order-items-field', 'gallery-field' => $this->value,
                default => (string)$this->value
            };
        }
    }

    public function resolvedForUpdate(): void
    {
        $this->resolvedForUpdate = match ($this->component) {
            'activity-field', 'activities-field', 'password-field' => null,
            'morph-one-field' => $this->resolved->id ?? null,
            'permissions-field', 'belong-to-many-field' => collect($this->resolved)->pluck('id'),
            default => $this->resolved
        };

        if(property_exists($this, 'default') && $this->default && !$this->resolvedForUpdate)
            $this->resolvedForUpdate = $this->default;

        if(property_exists($this, 'selected') && property_exists($this, 'options'))
            $this->selected = collect($this->options)->firstWhere('value', $this->value)['name'] ?? '';

        if(property_exists($this, 'badgeType') && property_exists($this, 'badges'))
            $this->badgeType = $this->badges[$this->value] ?? null;
    }

    public function resolvedForDisplay(): void
    {
        if(!$this->resolved) {
            $this->resolvedForDisplay = null;
        } else {
            $this->resolvedForDisplay = match ($this->component) {
                'image-field' => Storage::disk($this->getStorageDisk())->url($this->resolved),
                'activity-field', 'key-value-field' => json_decode($this->resolved),
                'textarea-field' => Str::limit($this->resolved, $this->limit),
                'morph-one-field' => $this->resolved->id,
                'permissions-field', 'belong-to-many-field' => collect($this->resolved)->pluck('id'),
                'timestamp-field' => $this->displayFormat === 'diffForHumans' ? Carbon::parse($this->value)->diffForHumans() : Carbon::parse($this->value)->format($this->displayFormat),
                default => $this->resolved
            };
        }
    }
}
