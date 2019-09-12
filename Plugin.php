<?php namespace Octobro\SuperCache;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function boot()
    {
        // Add RouteCacheMiddleware
        $this->app['Illuminate\Contracts\Http\Kernel']->pushMiddleware('Octobro\SuperCache\Classes\RouteCacheMiddleware');
    }
}
