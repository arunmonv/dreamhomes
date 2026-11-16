<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Adds browser-side protections to every response (clickjacking, MIME sniffing, XSS, mixed content)
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Public site runs no scripts at all; admin only needs inline confirm() handlers
        $script = $request->is('admin*') ? "'unsafe-inline'" : "'none'";
        $response->headers->add([
            'X-Frame-Options'        => 'DENY',
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy'        => 'strict-origin-when-cross-origin',
            'Permissions-Policy'     => 'camera=(), microphone=(), geolocation=(), payment=()',
            'Content-Security-Policy' => "default-src 'self'; img-src 'self' data:; style-src 'self' 'unsafe-inline'; script-src $script; form-action 'self' https://wa.me; frame-ancestors 'none'; base-uri 'self'; object-src 'none'",
        ]);
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }
        // Admin pages must never be cached by browsers/proxies
        if ($request->is('admin*')) {
            $response->headers->set('Cache-Control', 'no-store, private');
        }
        return $response;
    }
}
