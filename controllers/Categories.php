<?php namespace Lovata\Shopaholic\Controllers;

use Lovata\Import1cShopaholic\Classes\Import;
use Lovata\Import1cShopaholic\Classes\ImportCatalog;
use Queue;
use Backend\Classes\Controller;
use BackendMenu;
use Flash;
use Illuminate\Http\Request;
use Lang;
use Lovata\Shopaholic\Models\Category;
use System\Classes\PluginManager;

/**
 * Class Categories
 * @package Lovata\Shopaholic\Controllers
 * @author Denis Plisko, d.plisko@lovata.com, LOVATA Group
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Categories extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController',
        'Backend.Behaviors.RelationController',
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $relationConfig = [];

    /** @var Request */
    protected $obRequest;
    
    public function __construct(Request $obRequest)
    {
        $this->obRequest = $obRequest;

        //Add relation config for properties
        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            $this->relationConfig['product_property'] = \Lovata\PropertiesShopaholic\Models\Property::getRelationConfig();
            $this->relationConfig['offer_property'] = \Lovata\PropertiesShopaholic\Models\Property::getRelationConfig();
        }

        parent::__construct();
        BackendMenu::setContext('Lovata.Shopaholic', 'shopaholic-menu-main', 'shopaholic-menu-categories');
    }
}