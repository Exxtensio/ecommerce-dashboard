<?php

namespace Exxtensio\EcommerceDashboard\Providers\Includes;

use Illuminate\Support\ServiceProvider;
use Exxtensio\EcommerceDashboard\Console;

class ConsoleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\RunComposerCommand::class,
                Console\Commands\DemoCommand::class,
                Console\Commands\Demo\DemoTruncateCommand::class,
                Console\Commands\Demo\DemoStorageCommand::class,
                Console\Commands\Demo\DemoCountryCommand::class,
                Console\Commands\Demo\DemoBrandCommand::class,
                Console\Commands\Demo\DemoCategoryCommand::class,
                Console\Commands\Demo\DemoAttributeCommand::class,
                Console\Commands\Demo\DemoProductCommand::class,
                Console\Commands\Demo\DemoReviewCommand::class,
                Console\Commands\Demo\DemoCartCommand::class,
                Console\Commands\Demo\DemoRoleCommand::class,
                Console\Commands\Demo\DemoUserCommand::class,
                Console\Commands\Demo\DemoOrderCommand::class,
                Console\Commands\Demo\DemoDashboardCommand::class,
                Console\Commands\InstallCommand::class,
                Console\Commands\UpdateCommand::class,
                Console\Commands\TokenCommand::class,

                Console\Commands\CreateLinkAssetsCommand::class,
                Console\Commands\CreateLinkDataCommand::class,
                Console\Commands\UpdateCurrencyRateCommand::class,

                Console\Commands\CleanActivityLogCommand::class,
                Console\Commands\StatusCommand::class,
            ]);
        } else {
            $this->commands([
                Console\Commands\RunComposerCommand::class,
                Console\Commands\UpdateCommand::class,
                Console\Commands\StatusCommand::class,
            ]);
        }
    }

    public function register(): void {}
}
