<?php

use App\Http\Middleware\PrivateResponse;
use App\Http\Middleware\RequireAdmin;
use App\Http\Middleware\SetPublicLocale;
use App\Http\Requests\StoreInquiryRequest;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(PrivateResponse::class);
        $middleware->alias(['public.locale' => SetPublicLocale::class, 'admin' => RequireAdmin::class]);
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (Throwable $exception) {
            if (app()->bound('request') && request()->is('admin', 'admin/*', 'inquiries')) {
                // Never emit exception messages, SQL bindings, bodies or private notes.
                Log::error('Private request failed.', ['exception_type' => get_class($exception)]);

                return false;
            }
        });
        $exceptions->dontFlash(['current_password', 'password', 'password_confirmation', 'name', 'phone', 'email', 'message', 'internal_notes', 'submission_token', 'company_website']);
        $exceptions->render(function (HttpException $exception, Request $request) {
            if ($exception->getStatusCode() === 419 && $request->isMethod('POST') && $request->is('inquiries')) {
                return StoreInquiryRequest::feedback($request, 'contact.submission_expired');
            }
        });
    })->create();
