<?php

namespace Exxtensio\EcommerceDashboard\Traits;

use Illuminate\Support\Str;

trait ProductOptions
{
    protected static function getTypeDefault(): string
    {
        return self::getDefaultType() ?? self::DEFAULT_TYPE;
    }

    protected static function getStatuses(): array
    {
        return collect(self::getStatusesMap())
            ->keys()->mapWithKeys(fn($key) => [$key => Str::title(str_replace('_', ' ', $key))])
            ->toArray();
    }

    protected static function getStatusesMap(): array
    {
        return config('dashboard.product_statuses', [
            'inactive' => 'warning',
            'active' => 'success',
            'draft' => 'danger',
            'pre_order' => 'warning',
            'archived' => 'danger',
            'discontinued' => 'danger',
        ]);
    }

    protected static function getTypeOptions(): array
    {
        return self::toAcronymOptions(
        !self::getDefaultType()
            ? ['dig', 'qty', 'wt', 'vol', 'len']
            : [self::getDefaultType()]
        );
    }

    protected static function getUnitDependOnArray(): array
    {
        return [
            'options' => [
                'dig' => self::setUnitDependOnOptionsArray(self::DIGITAL_UNITS),
                'qty' => self::setUnitDependOnOptionsArray(self::QUANTITY_UNITS),
                'wt' => self::setUnitDependOnOptionsArray(self::WEIGHT_UNITS),
                'vol' => self::setUnitDependOnOptionsArray(self::VOLUME_UNITS),
                'len' => self::setUnitDependOnOptionsArray(self::LENGTH_UNITS),
            ],
            'default' => [
                'dig' => in_array(self::getDefaultUnit(), self::DIGITAL_UNITS) ? self::getDefaultUnit() : self::DEFAULT_DIGITAL_UNIT,
                'qty' => in_array(self::getDefaultUnit(), self::QUANTITY_UNITS) ? self::getDefaultUnit() : self::DEFAULT_QUANTITY_UNIT,
                'wt' => in_array(self::getDefaultUnit(), self::WEIGHT_UNITS) ? self::getDefaultUnit() : self::DEFAULT_WEIGHT_UNIT,
                'vol' => in_array(self::getDefaultUnit(), self::VOLUME_UNITS) ? self::getDefaultUnit() : self::DEFAULT_VOLUME_UNIT,
                'len' => in_array(self::getDefaultUnit(), self::LENGTH_UNITS) ? self::getDefaultUnit() : self::DEFAULT_LENGTH_UNIT,
            ]
        ];
    }

    protected static function toAcronymOptions($units, $resolve = false): array
    {
        if(!$resolve) {
            return collect($units)->mapWithKeys(fn($unit) => [
                $unit => Str::title(self::ACRONYM[$unit]),
            ])->toArray();
        } else {
            return collect($units)->map(fn($unit) => [
                'name' => Str::title(self::ACRONYM[$unit]),
                'value' => $unit,
            ])->values()->toArray();
        }
    }

    protected static function setUnitDependOnOptionsArray($haystack): array
    {
        return in_array(self::getDefaultUnit(), $haystack)
            ? self::toAcronymOptions([self::getDefaultUnit()], true)
            : self::toAcronymOptions($haystack, true);
    }
}
