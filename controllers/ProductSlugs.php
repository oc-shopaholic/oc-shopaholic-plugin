<?php namespace Lovata\Shopaholic\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Product Slugs Back-end Controller
 */
class ProductSlugs extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        //BackendMenu::setContext('Lovata.Shopaholic', 'shopaholic', 'productslugs');
        BackendMenu::setContext('Lovata.Shopaholic', 'shopaholic-menu-main', 'shopaholic-menu-products');
        
    }
}
