<?php

use App\Http\Controllers\Admin\AdminController as A;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

/* Public site */
Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/about-us', [SiteController::class, 'about'])->name('about');
Route::get('/properties',[SiteController::class, 'index'])->name('properties');
Route::get('/properties/type/{type}', [SiteController::class, 'index'])->name('properties.type');
Route::get('/properties/{property}', [SiteController::class, 'show'])->name('properties.show');
Route::post('/enquire', [SiteController::class, 'enquire'])->middleware('throttle:5,1')->name('enquire');

/* SEO */
Route::get('/sitemap.xml', [\App\Http\Controllers\SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [\App\Http\Controllers\SeoController::class, 'robots']);

/* Admin */
Route::prefix('admin')->group(function () {
    Route::get('login', [A::class, 'loginForm'])->name('login');
    Route::post('login', [A::class, 'login'])->middleware('throttle:10,1');

    Route::middleware('auth')->name('admin.')->group(function () {
        Route::post('logout', [A::class, 'logout'])->name('logout');

        Route::get('/', fn () => redirect()->route('admin.properties.index'));
        Route::get('properties', [A::class, 'propertiesIndex'])->name('properties.index');
        Route::get('properties/create', [A::class, 'propertyCreate'])->name('properties.create');
        Route::post('properties', [A::class, 'propertyStore'])->name('properties.store');
        Route::get('properties/{property}/edit', [A::class, 'propertyEdit'])->name('properties.edit');
        Route::put('properties/{property}', [A::class, 'propertyUpdate'])->name('properties.update');
        Route::delete('properties/{property}', [A::class, 'propertyDestroy'])->name('properties.destroy');

        Route::get('settings', [A::class, 'settingsForm'])->name('settings');
        Route::post('settings', [A::class, 'settingsSave'])->name('settings.save');

        Route::get('enquiries', [A::class, 'enquiries'])->name('enquiries');
        Route::delete('enquiries/{enquiry}', [A::class, 'enquiryDestroy'])->name('enquiries.destroy');
    });
});
