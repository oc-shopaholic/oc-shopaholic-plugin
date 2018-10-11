<?php namespace Lovata\Shopaholic\Controllers;

use Event;
use BackendMenu;
use Backend\Classes\Controller;

/**
 * Class PromoBlocks
 * @package Lovata\Shopaholic\Controllers
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class PromoBlocks extends Controller
{
    public $implement = [
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ReorderController',
        'Backend.Behaviors.RelationController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $relationConfig = 'config_relation.yaml';

    /**
     * PromoBlocks constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Lovata.Shopaholic', 'shopaholic-menu-promo', 'shopaholic-menu-promo-block');
    }

    /**
     * Ajax handler onReorder event
     *
     * @return mixed
     */
    public function onReorder()
    {
        $obResult = parent::onReorder();
        Event::fire('shopaholic.promo_block.update.sorting');

        return $obResult;
    }
}
