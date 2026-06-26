<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
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
        // En production (derrière le proxy HTTPS de Render), force la génération
        // d'URLs et d'actions de formulaire en https:// → supprime l'avertissement
        // « connexion non sécurisée » du navigateur.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

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
