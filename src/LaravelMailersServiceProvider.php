<?php

namespace Stenfrank\LaravelMailers;

use Illuminate\Mail\MailManager;
use Illuminate\Support\ServiceProvider;

class LaravelMailersServiceProvider extends ServiceProvider
{    
    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mails.php', 'mails');
        
        $this->app->singleton('dynamic.mailers', function($app) {
            return new DynamicMailers($app);
        });

        $this->app->bind('mailers', function ($app) {
            return $app->make('dynamic.mailers')->config();
        });
    }
    
    /**
     * Boot
     *
     * @return void
     */
    public function boot()
    {
        $this->app->resolving(MailManager::class, function (MailManager $mailManager, $app) {
            $app->make('mailers');

            return $mailManager;
        });
    }
}
