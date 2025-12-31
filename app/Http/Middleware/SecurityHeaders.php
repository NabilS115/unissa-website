<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // TEMPORARY: Disable CSP to test Alpine.js loading
        // Content Security Policy for production - Allow Alpine.js CDN
        // $csp = "default-src 'self'; " .
        //        "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:5173 https://cdn.jsdelivr.net https://unpkg.com; " .
        //        "style-src 'self' 'unsafe-inline' http://localhost:5173 https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
        //        "font-src 'self' https://fonts.gstatic.com; " .
        //        "img-src 'self' data: https: blob:; " .
        //        "connect-src 'self' http://localhost:5173 ws://localhost:5173 https:; " .
        //        "frame-src 'none'; " .
        //        "object-src 'none';";
        
        // $response->headers->set('Content-Security-Policy', $csp);
        
        // Strict Transport Security for HTTPS
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
        
        return $response;
    }
}