<?php

namespace Stenfrank\LaravelMailers;

use Illuminate\Support\Arr;
use Stenfrank\LaravelMailers\Contracts\Factory as DynamicFactory;

class DynamicMailers extends AbstractDynamicMailers implements DynamicFactory
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;
    
    /**
     * Mails
     *
     * @var array
     */
    protected $mails = [];
    
    /**
     * Configs
     *
     * @var array
     */
    protected $configs = [];
    
    /**
     * Mailers
     *
     * @var array
     */
    protected $mailers = [];
    
     /**
     * Construct
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Config
     *
     * @return void
     */
    public function config()
    {
        if (count($this->app['config']['mails.mailers']) > 1) {
            $config = $this->app['config']['mail'];
            $this->configs = $this->app['config']['mails'];
            $this->mails['default'] = $this->configs['mailers'][0];

            foreach ($this->configs['mailers'] ?? [] as $key => $name) {
                $this->mails['mailers'][$name] = $this->mailers[$name] ?? $this->setConfigs($name, $key);
            }

            $this->mails['sendmail'] = Arr::get($config, 'sendmail', null);
            $this->mails['markdown'] = Arr::get($config, 'markdown', null);
            $this->mails['log_channel'] = Arr::get($config, 'log_channel', null);

            $this->app['config']->set('mail', $this->mails);
        }
    }
}
