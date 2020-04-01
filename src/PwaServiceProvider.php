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


        if(isset($_GET['q'])) {
            $request['request'] = $_REQUEST;
            $q = $request['request']['q'];
            unset($request['request']['q']);

            $routes = [
                'evo-manifest.json' => ['EvolutionCMS\Dmi3yy\Pwa\Controllers\PwaController', 'manifest'],
                'evo-serviceworker.js' => ['EvolutionCMS\Dmi3yy\Pwa\Controllers\PwaController', 'serviceworker'],
            ];
            if (array_key_exists($q, $routes)) {
                call_user_func_array([new $routes[$q][0], $routes[$q][1]], $request);
            }
        }

    }
}
