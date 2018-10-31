<?php namespace Lovata\Shopaholic\Controllers;

use Event;
use BackendMenu;
use Backend\Classes\Controller;
use Backend\Classes\BackendController;
use Lovata\Shopaholic\Models\Brand;

/**
 * Class Brands
 * @package Lovata\Shopaholic\Controllers
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Brands extends Controller
{
    public $implement = [
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ReorderController',
        'Backend.Behaviors.ImportExportController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $importExportConfig = 'config_import_export.yaml';

    /**
     * Brands constructor.
     */
    public function __construct()
    {
        if (BackendController::$action == 'import') {
            Brand::extend(function ($obModel) {
                $obModel->rules['external_id'] = 'required';
            });
        }

        parent::__construct();
        BackendMenu::setContext('Lovata.Shopaholic', 'shopaholic-menu-main', 'shopaholic-menu-brands');
    }

    /**
     * Ajax handler onReorder event
     *
     * @return mixed
     */
    public function onReorder()
    {
        $obResult = parent::onReorder();
        Event::fire('shopaholic.brand.update.sorting');

        return $obResult;
    }
}
