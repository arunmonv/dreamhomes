<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        // Give every public view $s (site settings) and $wa (digits-only WhatsApp number).
        // A composer runs lazily at render time, so `artisan migrate` works before the table exists.
        View::composer(['layouts.site', 'home', 'listing', 'show', 'partials.*'], function ($view) {
            $s = Setting::all_();
            $view->with('s', $s)->with('wa', preg_replace('/\D/', '', $s['whatsapp'] ?? ''));
        });
    }
}
