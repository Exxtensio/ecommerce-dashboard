<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Exxtensio\EcommerceDashboard\Models\Product;
use Exxtensio\EcommerceDashboard\Models\ProductReview;
use Exxtensio\EcommerceDashboard\Models\User;

class DemoReviewCommand extends Command
{
    protected $signature = 'dashboard:demo-review';

    public function handle(): void
    {
        $this->info('Step 9: Processing reviews data...');
        $users = User::all();
        $products = Product::all();

        ProductReview::factory()
            ->count(5000)
            ->state(new Sequence(fn (Sequence $sequence) => [
                'user_id' => $users->random()->id,
                'product_id' => $products->random()->id,
            ]))
            ->create();
    }
}
