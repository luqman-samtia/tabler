<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use Illuminate\Support\Facades\View;

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
        DB::listen(function ($query) {
            Log::info($query->sql);
            Log::info($query->bindings);
        });
        View::composer('tablar::partials.header.notifications', function ($view) {
            $notifications = Notification::where('read', false)->latest()->take(5)->get();
            $notify =  Notification::where('is_new', 1)->orderBy('id', 'desc')->latest()->first();
            // $notify = $notify ? $notify->id : null;
            // dd($notifications);
             $view->with(['notifications'=> $notifications,
                            'notify'=>$notify
                ]);

        });


    }
}
