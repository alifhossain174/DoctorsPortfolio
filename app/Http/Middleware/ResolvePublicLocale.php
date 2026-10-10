<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolvePublicLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $selected = $request->query('lang');
        if (in_array($selected, ['en', 'bn'], true)) {
            $request->session()->put('locale', $selected);
        }
        $locale = $request->session()->get('locale') ?? SiteSetting::where('key', 'primary')->value('default_locale');
        app()->setLocale(in_array($locale, ['en', 'bn'], true) ? $locale : 'en');

        return $next($request);
    }
}
