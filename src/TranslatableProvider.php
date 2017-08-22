<?php namespace Brackets\Translatable;

use Brackets\Translatable\Facades\Translatable;
use Illuminate\Support\ServiceProvider;

class TranslatableProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(ViewComposerProvider::class);
        $this->app->register(TranslatableServiceProvider::class);

        $this->publishes([
            __DIR__.'/../install-stubs/config/translatable.php' => config_path('translatable.php'),
        ], 'config');
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
