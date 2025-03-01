<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;
use Inertia\Response;
use Exxtensio\EcommerceDashboard\Models\User;

class AuthController extends Controller
{
    public function showLoginForm(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function showRegisterForm(): Response
    {
        $artisan = User::whereHas('roles', function ($query) {
            $query->where('name', 'artisan');
        })->where('email', config('ecommerce.artisan.email'))->first();

        return Inertia::render('Auth/Register', [
            'exists' => (bool)$artisan,
            'artisan' => $artisan ? [
                'email' => null,
                'name' => null,
                'password' => null
            ] : [
                'email' => config('ecommerce.artisan.email'),
                'name' => config('ecommerce.artisan.name'),
                'password' => config('ecommerce.artisan.password')
            ]
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|max:255',
        ]);

        if (RateLimiter::tooManyAttempts('login|' . $request->ip(), 5)) {
            return response()->json([
                'message' => 'Too many login attempts. Please try again later.',
            ], 429);
        }

        if (Auth::attempt($request->only('email', 'password'), $request->get('remember'))) {
            RateLimiter::clear('login|' . $request->ip());
            return response()->json();
        }

        RateLimiter::hit('login|' . $request->ip());

        return response()->json([
            'message' => 'The provided credentials do not match our records.',
        ], 401);
    }

    public function signup(Request $request): JsonResponse
    {
        $request->validate([
            'install' => 'required|boolean',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|max:255|confirmed'
        ]);

        try {
            if($request->get('install')) {
                Artisan::call('migrate');
                $user = DB::transaction(function () use ($request) {
                    $user = User::where('name', $request->get('name'))
                        ->where('email', $request->get('email'))
                        ->first();

                    $user->assignRole('artisan');

                    return $user;
                });
            } else {
                $user = User::firstOrCreate([
                    'name' => $request->get('name'),
                    'email' => $request->get('email'),
                    'email_verified_at' => now(),
                    'password' => Hash::make($request->get('password'))
                ]);
            }
            Auth::login($user);
            return response()->json();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();

        return response()->json();
    }
}
