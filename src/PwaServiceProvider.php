<?php

namespace EvolutionCMS\Dmi3yy\Pwa;

use EvolutionCMS\ServiceProvider;

class PwaServiceProvider extends ServiceProvider
{
    /**
     * custom commands
     * @var array
     */
    protected $commands = [
        'EvolutionCMS\Dmi3yy\Pwa\Console\PwaCommand'
    ];


    public function register()
    {
        //register command for Artisan
        $this->commands($this->commands);
//        $this->loadPluginsFrom(
//            dirname(__DIR__) . '/plugins/'
//        );
        //echo 'test-pwa';
    }
}
