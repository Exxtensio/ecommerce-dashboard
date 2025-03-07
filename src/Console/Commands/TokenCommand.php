<?php

namespace Exxtensio\EcommerceDashboard\Console\Commands;

use Exxtensio\EcommerceDashboard\Models;
use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class TokenCommand extends Command
{
    protected $signature = 'dashboard:token';

    public function handle(): void
    {
        $user = Models\User::where('email', config('ecommerce.artisan.email'))
            ->first();

        if(!$user) {
            $this->error('User not found. Token retrieval is not possible.');
        } else {
            PersonalAccessToken::where('name', 'artisan')->delete();
            $token = $user->createToken('artisan');

            $this->info("Bearer Token: $token->plainTextToken");
            $this->error('Be careful!!!');
            $this->info('When reusing a command `php artisan dashboard:token`');
            $this->info('This will create a new `Bearer Token` and delete the old one');
            $this->info('Don\'t forget to save `Bearer Token`. You\'ll only see it once');
        }
    }
}
