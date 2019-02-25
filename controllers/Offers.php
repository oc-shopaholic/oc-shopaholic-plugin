<?php namespace Lovata\Shopaholic\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Backend\Classes\BackendController;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Classes\Helper\CurrencyHelper;

/**
 * Class Offers
 * @package Lovata\Shopaholic\Controllers
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Offers extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.RelationController',
        'Backend.Behaviors.ImportExportController',
    ];

    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $importExportConfig = 'config_import_export.yaml';

    /**
     * Products constructor.
     */
    public function __construct()
    {
        CurrencyHelper::instance()->disableActiveCurrency();

        if (BackendController::$action == 'import') {
            Offer::extend(function ($obModel) {
                $obModel->rules['external_id'] = 'required';
            });
        }

        parent::__construct();
        BackendMenu::setContext('Lovata.Shopaholic', 'shopaholic-menu-main', 'shopaholic-menu-products');
    }
}
