<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands;

use Exxtensio\EcommerceDashboard\Models;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    protected $signature = 'dashboard:install {--demo}';

    public function handle(): void
    {
        $this->callSilent('down', ['--render' => 'dashboard::errors.install']);
        $this->callSilent('optimize:clear');

        $this->comment('Step 1: Migrating...');
        $this->callSilent('migrate');
        $this->info('Migration completed successfully.');

        $this->comment('Step 2: Symbolic links creating...');
        $this->call('storage:link');
        $this->call('dashboard:link-assets');
        $this->call('dashboard:link-data');

        $this->comment('Step 3: Artisan role creating...');
        $role = $this->createRole();
        $this->info('Artisan role created successfully.');

        $this->comment('Step 4: Artisan user creating...');
        $this->createArtisan($role);
        $this->info('Artisan user created successfully.');

        $this->comment('Step 5: Processing permissions data...');
        $this->createPermissions();
        $this->info('Permissions data processed successfully.');

        $this->comment('Step 6: Processing currencies data...');
        $this->createCurrencies();
        $this->info('Currencies data processed successfully.');

        $this->comment('Step 7: Processing countries data...');
        $this->createCountries();
        $this->info('Countries data processed successfully.');

        $this->addEnvironmentVariables();

        if($this->option('demo')) {
            $this->comment('Step 8: Processing demo data...');
            $this->callSilent('dashboard:demo');
            $this->info('Demo data processed successfully.');
        }

        $this->callSilent('up');
        $this->info('All steps completed successfully.');
    }

    protected function createRole()
    {
        return Models\Role::firstOrCreate(
            ['name' => 'artisan'],
            ['guard_name' => 'web']
        );
    }

    protected function createArtisan($role): void
    {
        $user = Models\User::firstOrCreate(
            ['email' => config('ecommerce.artisan.email')],
            [
                'name' => config('ecommerce.artisan.name'),
                'email_verified_at' => now(),
                'password' => bcrypt(config('ecommerce.artisan.password')),
            ],
        );
        if(!$user->hasRole($role))
            $user->roles()->attach($role->id);
    }

    protected function createPermissions(): void
    {
        $collection = collect([
            'global' => ['viewDashboard'],
            'activity' => ['Activity viewAny', 'Activity view'],
            'cart' => ['Cart viewAny', 'Cart view'],
            'order' => ['Order viewAny', 'Order view'],
            'country' => ['Country viewAny', 'Country view', 'Country create', 'Country update'],
            'currency' => ['Currency viewAny', 'Currency view', 'Currency create', 'Currency update'],
            'permission' => ['Permission viewAny', 'Permission view', 'Permission create', 'Permission update', 'Permission delete', 'Permission restore', 'Permission forceDelete',],
            'product' => ['Product viewAny', 'Product view', 'Product create', 'Product update', 'Product delete', 'Product restore', 'Product forceDelete'],
            'productAttribute' => ['ProductAttribute viewAny', 'ProductAttribute view', 'ProductAttribute create', 'ProductAttribute update', 'ProductAttribute delete', 'ProductAttribute restore', 'ProductAttribute forceDelete'],
            'productBrand' => ['ProductBrand viewAny', 'ProductBrand view', 'ProductBrand create', 'ProductBrand update', 'ProductBrand delete', 'ProductBrand restore', 'ProductBrand forceDelete'],
            'productCategory' => ['ProductCategory viewAny', 'ProductCategory view', 'ProductCategory create', 'ProductCategory update', 'ProductCategory delete', 'ProductCategory restore', 'ProductCategory forceDelete'],
            'productReview' => ['ProductReview viewAny', 'ProductReview view', 'ProductReview update', 'ProductReview delete', 'ProductReview restore', 'ProductReview forceDelete'],
            'role' => ['Role viewAny', 'Role view', 'Role create', 'Role update', 'Role delete', 'Role restore', 'Role forceDelete'],
            'user' => ['User viewAny', 'User view', 'User create', 'User update', 'User delete', 'User restore', 'User forceDelete']
        ]);

        $collection->map(function ($item, $group) {
            collect($item)->map(function ($i) use ($group) {
                Models\Permission::firstOrCreate(
                    ['name' => $i],
                    ['group' => $group]
                );
            });
        });
    }

    protected function createCurrencies(): void
    {
        $currencies = File::get(__DIR__ . '/../../../currencies.json');
        collect(json_decode($currencies, true))
            ->each(function ($currency) {
                Models\Currency::firstOrCreate(
                    ['code' => $currency['code']],
                    [
                        'name' => $currency['name'],
                        'code' => $currency['code'],
                        'symbol' => $currency['symbol'],
                    ]
                );
            });

        $this->call('dashboard:update-currency-rate');
    }

    protected function createCountries(): void
    {
        $countries = File::get(__DIR__ . '/../../../countries.json');
        Models\Country::withoutEvents(function () use ($countries) {
            collect(json_decode($countries, true))
                ->each(function ($country) {
                    Models\Country::firstOrCreate(
                        ['code' => $country['code']],
                        [
                            'name' => $country['name'],
                            'code' => $country['code'],
                            'active' => $country['code'] === config('ecommerce.default.country'),
                        ]
                    );
                });
        });

        Models\Country::findByCode(config('ecommerce.default.country'))
            ->currency()
            ->associate(Models\Currency::findByCode(config('ecommerce.default.currency')))
            ->save();
    }

    protected function addEnvironmentVariables(): void
    {
        if (File::missing($env = app()->environmentFile())) {
            return;
        }

        $host = str_replace(['http://', 'https://'], '', config('app.url'));
        $contents = File::get($env);
        $variables = Arr::where([
            'REVERB_APP_ID' => 'REVERB_APP_ID=1001',
            'REVERB_APP_KEY' => 'REVERB_APP_KEY=sellexx',
            'REVERB_APP_SECRET' => 'REVERB_APP_SECRET=${SELLEXX_DASHBOARD_KEY}',
            'REVERB_HOST' => "REVERB_HOST=$host",
            'REVERB_PORT' => 'REVERB_PORT=8080',
            'REVERB_SCHEME' => 'REVERB_SCHEME=http'
        ], function ($value, $key) use ($contents) {
            return ! Str::contains($contents, PHP_EOL.$key);
        });

        $variables = trim(implode(PHP_EOL, $variables));

        if ($variables === '') {
            return;
        }

        File::append(
            $env,
            Str::endsWith($contents, PHP_EOL) ? PHP_EOL.$variables.PHP_EOL : PHP_EOL.PHP_EOL.$variables.PHP_EOL,
        );
    }
}
