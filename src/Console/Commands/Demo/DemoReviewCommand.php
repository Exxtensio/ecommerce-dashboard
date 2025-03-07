<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Exxtensio\EcommerceDashboard\Models;

class DemoReviewCommand extends Command
{
    protected $signature = 'dashboard:demo-review';

    public function handle(): void
    {
        $this->info('Step 10: Processing reviews data...');
        $users = Models\User::all();
        $products = Models\Product::all();

        Models\ProductReview::factory()
            ->count(3000)
            ->state(new Sequence(fn (Sequence $sequence) => [
                'user_id' => $users->random()->id,
                'product_id' => $products->random()->id,
            ]))
            ->create();
    }
}
