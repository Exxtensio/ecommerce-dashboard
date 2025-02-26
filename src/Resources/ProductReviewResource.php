<?php

namespace Exxtensio\EcommerceDashboard\Resources;

use Illuminate\Http\Request;
use Exxtensio\EcommerceDashboard\Fields;
use Exxtensio\EcommerceDashboard\Filters;
use Exxtensio\EcommerceDashboard\Http\Controllers\ProductReviewController;
use Exxtensio\EcommerceDashboard\Models;

class ProductReviewResource extends Resource
{
    public static string $model = Models\ProductReview::class;
    public static string $controller = ProductReviewController::class;
    public static string $title = 'id';
    public static string $label = 'Reviews';
    public static string $prefix = 'reviews';
    public static string $singularLabel = 'Review';
    public static array $search = ['id', 'comment'];
    public static bool $canCreate = false;
    public static array $defaultColumns = ['id', 'user_id', 'product_id', 'rating', 'comment', 'created_at', 'deleted_at'];
    public static array $defaultRelations = ['user', 'product'];

    public array $withOptions = [
        UserResource::class => Models\User::class,
        ProductResource::class => Models\Product::class
    ];

    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            Fields\ID::make('ID', 'id', $this)
                ->copyable(),

            Fields\Rating::make('Rating', 'rating', $this)
                ->fullWidth(),

            Fields\BelongTo::make('User', 'user', $this, UserResource::class)
                ->readonly(),

            Fields\BelongTo::make('Product', 'product', $this, ProductResource::class)
                ->readonly(),

            Fields\Textarea::make('Comment', 'comment', $this)
                ->rows(6)
                ->fullWidth(),

            Fields\Activities::make('Last Activities', 'activities', $this, self::class)
                ->hideFromIndex()->hideWhenCreating(),

            Fields\Timestamp::make('Created At', 'created_at', $this)
                ->sortable()
                ->diffForHumans(),

            Fields\Timestamp::make('Updated At', 'updated_at', $this)
                ->sortable()
                ->diffForHumans(),

            Fields\Timestamp::make('Deleted At', 'deleted_at', $this)
                ->diffForHumans()
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function filters(Request $request): array
    {
        return [
            Filters\Select::make('User', 'users', 'user')
                ->searchable(),

            Filters\Select::make('Product', 'products', 'product')
                ->searchable(),

            Filters\Rating::make('Rating', 'rating')
        ];
    }
}
