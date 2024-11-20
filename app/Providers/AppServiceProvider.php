<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Feedback;
use Carbon\Carbon;

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
    public function boot()
    {

        Carbon::setLocale('id');

        View::composer('*', function ($view) {
            $unreadFeedbackCount = Feedback::where('is_read', false)->count();
            $view->with('unreadFeedbackCount', $unreadFeedbackCount);
        });
    }
}
