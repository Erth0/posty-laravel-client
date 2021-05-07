<?php

namespace Mukja\Posty;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Mukja\Posty\Http\Controllers\TagsController;
use Mukja\Posty\Commands\GenerateApiTokenCommand;
use Mukja\Posty\Http\Controllers\TopicsController;
use Mukja\Posty\Http\Controllers\ArticlesController;
use Mukja\Posty\Http\Middleware\AuthenticatePostyMiddleware;
use Mukja\Posty\Http\Controllers\TestPostyConnectionController;

class PostyServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'mukja');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'mukja');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->registerApiRoutes();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/posty.php', 'posty');

        // Register the service the package provides.
        $this->app->singleton('posty', function ($app) {
            return new Posty;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['posty'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/posty.php' => config_path('posty.php'),
        ], 'posty.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/mukja'),
        ], 'posty.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/mukja'),
        ], 'posty.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/mukja'),
        ], 'posty.views');*/

        // Registering package commands.
        $this->commands([GenerateApiTokenCommand::class]);
    }

    private function registerApiRoutes()
    {
        Route::group([
            'as' => 'posty.',
            'prefix' => config('posty.posty_endpoint_prefix', '_posty'),
            'middleware' => [AuthenticatePostyMiddleware::class],
        ], function () {
            Route::get('test', TestPostyConnectionController::class);

            Route::get('articles/{id}', [ArticlesController::class, 'show']);
            Route::post('articles', [ArticlesController::class, 'store']);
            Route::put('articles/{id}', [ArticlesController::class, 'update']);
            Route::delete('articles/{id}', [ArticlesController::class, 'destroy']);

            Route::get('topics', [TopicsController::class, 'index']);
            Route::get('topics/{id}', [TopicsController::class, 'show']);
            Route::post('topics', [TopicsController::class, 'store']);
            Route::put('topics/{id}', [TopicsController::class, 'update']);
            Route::delete('topics/{id}', [TopicsController::class, 'destroy']);

            Route::get('tags', [TagsController::class, 'index']);
            Route::get('tags/{id}', [TagsController::class, 'show']);
            Route::post('tags', [TagsController::class, 'store']);
            Route::put('tags/{id}', [TagsController::class, 'update']);
            Route::delete('tags/{id}', [TagsController::class, 'destroy']);
        });
    }
}
