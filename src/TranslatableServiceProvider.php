<?php namespace Brackets\Translatable;

use Brackets\Translatable\Facades\Translatable;
use Illuminate\Support\ServiceProvider;
use Brackets\Translatable\Providers\ViewComposerProvider;
use Brackets\Translatable\Providers\TranslatableProvider;

class TranslatableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../install-stubs/config/translatable.php' => config_path('translatable.php'),
            ], 'config');
        }

        $this->app->register(ViewComposerProvider::class);
        $this->app->register(TranslatableProvider::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../install-stubs/config/translatable.php', 'translatable'
        );

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Translatable', Translatable::class);
    }
}
