<?php namespace Octobro\SuperCache\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Artisan;
use Flash;

class CacheRoutes extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController'
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = [
        'octobro.supercache.manage_cacheroute'
    ];

    /**
     * CacheRoutes constructor.
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Octobro.SuperCache', 'CacheRoute');
    }

    /**
     *
     */
    public function onClear()
    {
        Artisan::call('cache:clear');
        Flash::success('Clear Cache');
    }
}
