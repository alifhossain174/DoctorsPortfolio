<?php

namespace App\Providers;

use App\Http\Requests\StoreInquiryRequest;
use App\Models\User;
use App\Services\WebsiteContent;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(WebsiteContent::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('inquiries', fn (Request $request) => Limit::perMinute(10)->by('inquiry:'.hash('sha256', (string) $request->ip()))
            ->response(fn ($request, $headers) => StoreInquiryRequest::feedback($request, 'contact.rate_limited')->withHeaders($headers))
        );
        Gate::define('access-admin', fn (User $user): bool => $user->is_admin === true);
        Model::preventSilentlyDiscardingAttributes();
    }
}
