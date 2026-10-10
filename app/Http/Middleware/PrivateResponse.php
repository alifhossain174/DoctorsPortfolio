<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class PrivateResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = $request->is('admin', 'admin/*');
        $submission = $request->is('inquiries') && $request->isMethod('POST');
        $contact = $request->is('contact', 'contact.html');
        if ($admin || $submission || $contact) {
            // Enforced before binding/auth/validation, even when local APP_DEBUG is true.
            config(['app.debug' => false]);
        }
        $response = $next($request);
        if ($admin || $submission || $contact) {
            $response->headers->set('Cache-Control', 'private, no-store, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            $response->headers->set('Referrer-Policy', 'no-referrer');
        }
        if ($admin || $submission) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow, nosnippet');
        }

        return $response;
    }
}
