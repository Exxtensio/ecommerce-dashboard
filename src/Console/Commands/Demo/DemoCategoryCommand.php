<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands\Demo;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Str;
use Exxtensio\EcommerceDashboard\Models\ProductCategory;

class DemoCategoryCommand extends Command
{
    protected $signature = 'dashboard:demo-category';

    public function handle(): void
    {
        $this->info('Step 5: Processing categories data...');

        ProductCategory::factory()->count(10)->sequence(fn (Sequence $sequence) => [
            'name' => "Category $sequence->index",
            'slug' => Str::slug("Category $sequence->index")
        ])->create();

        foreach ([10, 10, 10, 10, 10, 10, 10, 10, 10] as $count) {
            ProductCategory::factory()
                ->count($count)
                ->state(new Sequence(fn (Sequence $sequence) => ['parent_id' => ProductCategory::all()->random()->id]))
                ->sequence(function (Sequence $sequence) {
                    $index = $sequence->index + ProductCategory::count();
                    return ['name' => "Category $index", 'slug' => Str::slug("Category $index")];
                })->create();
        }
    }
}
