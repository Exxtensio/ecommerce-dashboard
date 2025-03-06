<?php

namespace Exxtensio\EcommerceDashboard\Models;

use Exxtensio\EcommerceCore\Traits\HasAnotherPrimaryKey;
use Exxtensio\EcommerceDashboard\Traits;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasAnotherPrimaryKey,
        Traits\DashboardTotalOptions,
        Traits\DashboardHorizontalOptions,
        Traits\DashboardPieOptions;

    protected $table = 'dashboard';

    protected $fillable = [
        'title',
        'chart',
        'type',
        'position',
        'query',
        'start',
        'end'
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    protected $appends = [
        'response'
    ];

    public function getResponseAttribute(): array
    {
        $query = explode(':', $this->query);

        if ($this->start === $this->end) {
            $dates = [$this->start->startOfDay(), $this->start->endOfDay()];
            $diffInDays = [$this->start->subDay()->startOfDay(), $this->start->subDay()->endOfDay()];
        } else {
            $diff = $this->start->diffInDays($this->end)+1;
            $dates = [$this->start->startOfDay(), $this->end->endOfDay()];
            $diffInDays = [$this->start->subDays($diff)->startOfDay(), $this->end->subDays($diff)->endOfDay()];
        }

        return match ($this->type) {
            'total' => match ($query[0] ?? null) {
                'users' => $this->getTotalUsersResponse($query, $dates, $diffInDays),
                'brands' => $this->getTotalBrandsResponse($dates, $diffInDays),
                'categories' => $this->getTotalCategoriesResponse($dates, $diffInDays),
                'attributes' => $this->getTotalAttributesResponse($dates, $diffInDays),
                'reviews' => $this->getTotalReviewsResponse($query, $dates, $diffInDays),
                'products' => $this->getTotalProductsResponse($query, $dates, $diffInDays),
                'carts' => $this->getTotalCartsResponse($query, $dates, $diffInDays),
                'orders' => $this->getTotalOrdersResponse($query, $dates, $diffInDays),
                default => []
            },
            'horizontal' => match ($query[0] ?? null) {
                'orders' => match ($this->chart ?? null) {
                    'exx-simple-horizontal-bar-card' => $this->getSimpleHorizontalBarCardResponse($query, $dates),
                    'exx-simple-line-card' => $this->getSimpleLineCardResponse($query, $dates)
                },
                default => []
            },
            'pie' => match ($query[0] ?? null) {
                'orders' => match ($this->chart ?? null) {
                    'exx-simple-pie-card' => $this->getPieCardResponse($query, $dates, 8),
                    'exx-pie-with-variable-radius-card' => $this->getPieCardResponse($query, $dates, 5),
                    'exx-simple-donut-card' => $this->getPieCardResponse($query, $dates, 10),
                },
                default => []
            },
            default => []
        };
    }
}
