<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Services\AuditLogService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register a view namespace 'layouts' so Livewire's 'layouts::app' resolves
        View::addNamespace('layouts', resource_path('views/components/layouts'));

        // Listen for login/logout events to create audit logs
        Event::listen(Login::class, function (Login $event) {
            try {
                AuditLogService::log(AuditLogService::ACTION_LOGIN, [
                    'user_id' => $event->user->id,
                    'message' => 'User logged in',
                ]);
            } catch (\Throwable $e) {
                // swallow to avoid breaking auth flow
            }
        });

        Event::listen(Logout::class, function (Logout $event) {
            try {
                AuditLogService::log(AuditLogService::ACTION_LOGOUT, [
                    'user_id' => $event->user?->id ?? null,
                    'message' => 'User logged out',
                ]);
            } catch (\Throwable $e) {
                // swallow
            }
        });
    }
}
