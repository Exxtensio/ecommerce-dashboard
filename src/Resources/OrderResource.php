<?php

namespace Exxtensio\EcommerceDashboard\Resources;

use Illuminate\Http\Request;
use Exxtensio\EcommerceDashboard\Fields;
use Exxtensio\EcommerceDashboard\Http\Controllers\OrderController;
use Exxtensio\EcommerceDashboard\Models\Order;

class OrderResource extends Resource
{
    public static string $model = Order::class;
    public static string $controller = OrderController::class;
    public static string $label = 'Orders';
    public static string $prefix = 'orders';
    public static string $singularLabel = 'Order';
    public static array $search = ['id'];
    public static bool $canCreate = false;
    public static bool $canDelete = false;

    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            Fields\ID::make('ID', 'id', $this)
                ->copyable(),

            Fields\Text::make('Amount', 'amount', $this),

            Fields\Text::make('Status', 'status', $this),

            Fields\Text::make('Payment Status', 'payment_status', $this),

            Fields\Activities::make('Last Activities', 'activities', $this, self::class)
                ->hideFromIndex()->hideWhenCreating(),

            Fields\Timestamp::make('Created At', 'created_at', $this)
                ->sortable()
                ->diffForHumans(),

            Fields\Timestamp::make('Updated At', 'updated_at', $this)
                ->sortable()
                ->diffForHumans()
        ];
    }
}
