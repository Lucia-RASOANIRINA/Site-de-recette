<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\StatsController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Partage la "recette de la semaine" avec le widget de footer (toutes pages).
        View::composer('layouts.partials.weekly-recipe', function ($view) {
            try {
                $ranking = StatsController::weeklyRanking(3);
                $view->with('weeklyTop', $ranking->first());
                $view->with('weeklyRanking', $ranking);
            } catch (\Throwable $e) {
                $view->with('weeklyTop', null);
                $view->with('weeklyRanking', collect());
            }
        });
    }
}
