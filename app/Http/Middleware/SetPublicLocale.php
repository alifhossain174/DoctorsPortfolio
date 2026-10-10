<?php

namespace App\Http\Middleware;

use App\Services\WebsiteContent;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetPublicLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $this->select($request);

        return $next($request);
    }

    public function select(Request $request): void
    {
        $selected = $request->query('locale');
        $stored = $request->hasSession() ? $request->session()->get('public_locale') : null;
        $default = app(WebsiteContent::class)->settings?->default_locale;
        $locale = collect([$selected, $stored, $default, 'en'])->first(fn ($value) => in_array($value, ['en', 'bn'], true));
        app()->setLocale($locale);
        if ($request->hasSession()) {
            $request->session()->put('public_locale', $locale);
        }
    }
}
