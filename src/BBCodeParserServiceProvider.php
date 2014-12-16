<?php namespace Golonka\BBCode;

use Illuminate\Support\ServiceProvider;

class BBCodeParserServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['bbcode'] = $this->app->share(
            function ($app) {
                return new BBCodeParser;
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('bbcode');
    }
}
