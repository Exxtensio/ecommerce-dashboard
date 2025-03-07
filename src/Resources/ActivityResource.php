<?php

namespace Exxtensio\EcommerceDashboard\Resources;

use Illuminate\Http\Request;
use Exxtensio\EcommerceDashboard\Fields;
use Exxtensio\EcommerceDashboard\Http\Controllers\ActivityController;
use Exxtensio\EcommerceDashboard\Models;

class ActivityResource extends Resource
{
    public static string $model = Models\Activity::class;
    public static string $controller = ActivityController::class;
    public static string $prefix = 'activities';
    public static string $label = 'Activities';
    public static string $singularLabel = 'Activity';
    public static array $search = ['id', 'description', 'properties'];
    public static bool $canCreate = false;
    public static bool $canEdit = false;
    public static bool $canDelete = false;

    public static array $defaultColumns = ['id', 'subject_id', 'subject_type', 'causer_id', 'causer_type', 'description', 'event', 'created_at'];
    public static array $defaultRelations = ['subject', 'causer'];

    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            Fields\ID::make('ID', 'id', $this)
                ->copyable(),

            Fields\MorphTo::make('Subject', 'subject', $this, [
                CartResource::class,
                CountryResource::class,
                CurrencyResource::class,
                OrderResource::class,
                PermissionResource::class,
                ProductAttributeResource::class,
                ProductBrandResource::class,
                ProductCategoryResource::class,
                ProductResource::class,
                ProductReviewResource::class,
                RoleResource::class,
                UserResource::class,
            ])->readonly(),

            Fields\MorphTo::make('Causer', 'causer', $this, UserResource::class)
                ->readonly(),

            Fields\Text::make('Log Name', 'log_name', $this)
                ->rules(['required'])
                ->readonly(),

            Fields\Text::make('Description', 'description', $this)
                ->readonly(),

            Fields\Text::make('Event', 'event', $this)
                ->rules(['required'])
                ->readonly(),

            Fields\Activity::make('Properties', 'properties', $this)
                ->fullWidth()
                ->panel('properties'),

            Fields\Timestamp::make('Created At', 'created_at', $this)
                ->sortable()
                ->diffForHumans(),

            Fields\Timestamp::make('Updated At', 'updated_at', $this)
                ->sortable()
                ->diffForHumans(),
        ];
    }
}
