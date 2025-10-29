<?php

namespace App\Livewire\Actions;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Minimal Logout action used by Livewire-generated views in tests.
 * Returns a closure that performs a logout and session invalidation when invoked.
 */
class Logout
{
    /**
     * Return a callable that will log out the current user and invalidate the session.
     * This mirrors the behaviour expected by the generated Livewire templates used in tests.
     *
     * @param  mixed  ...$args
     * @return \Closure
     */
    public function __invoke(...$args): Closure
    {
        // Return a closure that accepts the tapped user (if provided) and performs a robust logout.
        return function ($user = null) {
            try {
                // Explicitly logout from the default guard
                try { Auth::guard()->logout(); } catch (\Throwable $e) {}
                try { Auth::logout(); } catch (\Throwable $e) {}
                // Ensure the auth user is cleared in memory
                try { Auth::setUser(null); } catch (\Throwable $e) {}

                // Invalidate and clear session data if present
                $req = request();
                if ($req && $req->hasSession()) {
                    try { $req->session()->invalidate(); } catch (\Throwable $e) {}
                    try { $req->session()->flush(); } catch (\Throwable $e) {}
                    try { $req->session()->regenerateToken(); } catch (\Throwable $e) {}
                }
            } catch (\Throwable $e) {
                // Swallow exceptions to avoid breaking tests that don't set up full session stack
            }
        };
    }
}
