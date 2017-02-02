<?php namespace Lovata\Shopaholic\Controllers;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Settings;
use Yaml;
use Backend\Classes\Controller;
use BackendMenu;
use Flash;
use Illuminate\Http\Request;
use Lang;
use Lovata\Shopaholic\Models\Product;
use System\Classes\PluginManager;

/**
 * Class Products
 * @package Lovata\Shopaholic\Controllers
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Products extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController'
    ];
    
    public $listConfig;
    public $formConfig;
    public $relationConfig = [];

    /**
     * Products constructor.
     */
    public function __construct()
    {
        $this->getListConfig();
        $this->getFormConfig();
        $this->getRelationConfig();

        //Add relation config
        //TODO: Перенести в плагин
        if(PluginManager::instance()->hasPlugin('Lovata.RelatedProductsShopaholic')) {
            // for related products
            $this->relationConfig['rel_products'] = \Lovata\RelatedProductsShopaholic\Models\RelationProduct::getRelationConfig();
        }
        
        parent::__construct();
        BackendMenu::setContext('Lovata.Shopaholic', 'shopaholic-menu-main', 'shopaholic-menu-products');
    }

    /**
     * Get $listConfig
     */
    protected function getListConfig()
    {
        //Get controller config
        $arConfig = Yaml::parseFile(__DIR__.'/products/config_list.yaml');

        //Get model config
        $arConfig['list'] = Yaml::parseFile(base_path().'/plugins/lovata/shopaholic/models/product/columns.yaml');

        //Hide fields
        $arConfiguredViewFields = Product::getConfiguredBackendFields();
        if(!empty($arConfiguredViewFields)) {
            foreach($arConfiguredViewFields as $sFieldKey => $sFieldName) {

                $sSettingName = 'product_'.$sFieldKey;
                if(in_array($sFieldKey, ['category', 'brand'])) {
                    $sFieldKey = $sFieldKey.'_id';
                }

                if(isset($arConfig['list']['columns'][$sFieldKey]) && Settings::getValue($sSettingName)) {
                    unset($arConfig['list']['columns'][$sFieldKey]);
                }
            }
        }

        $this->listConfig = ['list' => $arConfig];
    }

    /**
     * Get $formConfig
     */
    protected function getFormConfig()
    {
        //Get controller config
        $arConfig = Yaml::parseFile(__DIR__.'/products/config_form.yaml');

        //Get model config
        $arConfig['form'] = Yaml::parseFile(base_path().'/plugins/lovata/shopaholic/models/product/fields.yaml');

        //Hide fields
        $arConfiguredViewFields = Product::getConfiguredBackendFields();
        if(!empty($arConfiguredViewFields)) {
            foreach($arConfiguredViewFields as $sFieldKey => $sFieldName) {
                if(isset($arConfig['form']['tabs']['fields'][$sFieldKey]) && Settings::getValue('product_'.$sFieldKey)) {
                    unset($arConfig['form']['tabs']['fields'][$sFieldKey]);
                }
            }
        }

        $this->formConfig = $arConfig;
    }

    /**
     * Get $relationConfig
     */
    protected function getRelationConfig()
    {
        //Get relation config
        $arConfig = Yaml::parseFile(__DIR__.'/products/config_relation.yaml');

        //Get model config
        $arFormConfig = Yaml::parseFile(base_path().'/plugins/lovata/shopaholic/models/offer/fields.yaml');
        $arListConfig = Yaml::parseFile(base_path().'/plugins/lovata/shopaholic/models/offer/columns.yaml');

        //Hide fields
        $arConfiguredViewFields = Offer::getConfiguredBackendFields();
        if(!empty($arConfiguredViewFields)) {
            foreach($arConfiguredViewFields as $sFieldKey => $sFieldName) {

                if(isset($arListConfig['columns'][$sFieldKey]) && Settings::getValue('offer_'.$sFieldKey)) {
                    unset($arListConfig['columns'][$sFieldKey]);
                }

                if(isset($arFormConfig['tabs']['fields'][$sFieldKey]) && Settings::getValue('offer_'.$sFieldKey)) {
                    unset($arFormConfig['tabs']['fields'][$sFieldKey]);
                }
            }
        }

        //Add model config
        $arConfig['offers']['manage']['form'] = $arFormConfig;
        $arConfig['offers']['manage']['list'] = $arListConfig;
        $arConfig['offers']['view']['list'] = $arListConfig;

        $this->relationConfig = $arConfig;
    }
}