<?php

namespace Exxtensio\EcommerceDashboard\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Exxtensio\EcommerceDashboard\Fields;

abstract class Resource extends JsonResource
{
    public static string $title = 'id';
    public static array $search = ['id'];
    public static array $perPage = [10, 15, 25, 50, 100];

    public static bool $canCreate = true;
    public static bool $canPreview = true;
    public static bool $canEdit = true;
    public static bool $canDelete = true;
    protected static ?int $limitations = null;

    public function getModel(): mixed
    {
        return static::$model;
    }

    public static function getLimitations(): ?int
    {
        return static::$limitations;
    }

    public static function hasLimitations(): bool
    {
        return static::$limitations !== null;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            Fields\ID::make('ID', 'id', $this)
        ];
    }

    public function filters(Request $request): array
    {
        return [];
    }
}
