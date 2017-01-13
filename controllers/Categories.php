<?php namespace Lovata\Shopaholic\Controllers;

use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Settings;
use Yaml;
use Backend\Classes\Controller;
use BackendMenu;
use System\Classes\PluginManager;

/**
 * Class Categories
 * @package Lovata\Shopaholic\Controllers
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
    
    public $listConfig;
    public $formConfig;
    public $reorderConfig = 'config_reorder.yaml';
    public $relationConfig = [];

    /**
     * Categories constructor.
     */
    public function __construct()
    {
        $this->getListConfig();
        $this->getFormConfig();

        //Add relation config for properties
        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            \Lovata\PropertiesShopaholic\Classes\CategoryExtend::relationConfigExtend($this);
        }

        parent::__construct();
        BackendMenu::setContext('Lovata.Shopaholic', 'shopaholic-menu-main', 'shopaholic-menu-categories');
    }

    /**
     * Get $listConfig
     */
    protected function getListConfig()
    {
        //Get controller config
        $arConfig = Yaml::parseFile(__DIR__.'/categories/config_list.yaml');

        //Get model config
        $arConfig['list'] = Yaml::parseFile(base_path().'/plugins/lovata/shopaholic/models/category/columns.yaml');

        //Hide fields
        $arConfiguredViewFields = Category::getConfiguredBackendFields();
        if(!empty($arConfiguredViewFields)) {
            foreach($arConfiguredViewFields as $sFieldKey => $sFieldName) {
                if(isset($arConfig['list']['columns'][$sFieldKey]) && Settings::getValue('category_'.$sFieldKey)) {
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
        $arConfig = Yaml::parseFile(__DIR__.'/categories/config_form.yaml');

        //Get model config
        $arConfig['form'] = Yaml::parseFile(base_path().'/plugins/lovata/shopaholic/models/category/fields.yaml');

        //Hide fields
        $arConfiguredViewFields = Category::getConfiguredBackendFields();
        if(!empty($arConfiguredViewFields)) {
            foreach($arConfiguredViewFields as $sFieldKey => $sFieldName) {
                if(isset($arConfig['form']['tabs']['fields'][$sFieldKey]) && Settings::getValue('category_'.$sFieldKey)) {
                    unset($arConfig['form']['tabs']['fields'][$sFieldKey]);
                }
            }
        }

        $this->formConfig = $arConfig;
    }
}