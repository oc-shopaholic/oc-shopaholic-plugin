<?php namespace Lovata\Shopaholic;

use Event;
use Lovata\Shopaholic\Models\Settings;
use System\Classes\PluginBase;

/**
 * Class Plugin
 * @package Lovata\Shopaholic
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Plugin extends PluginBase
{
    const NAME = 'shopaholic';
    const CACHE_TAG = 'shopaholic';

    /** @var array Plugin dependencies */
    public $require = ['Lovata.Toolbox'];

    /**
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Lovata\Shopaholic\Components\ProductList'  => 'ProductList',
            'Lovata\Shopaholic\Components\CategoryList' => 'CategoryList',
            'Lovata\Shopaholic\Components\CategoryPage' => 'CategoryPage',
            'Lovata\Shopaholic\Components\CategoryData' => 'CategoryData',
            'Lovata\Shopaholic\Components\Breadcrumbs'  => 'CatalogBreadcrumbs',
            'Lovata\Shopaholic\Components\ProductData'  => 'ProductData',
            'Lovata\Shopaholic\Components\ProductPage'  => 'ProductPage',
            'Lovata\Shopaholic\Components\Currency'     => 'Currency',
        ];
    }

    /**
     * @return array
     */
    public function registerSettings()
    {
        return [
            'config' => [
                'label'       => 'lovata.shopaholic::lang.plugin.name',
                'description' => 'lovata.shopaholic::lang.plugin.description',
                'icon'        => 'oc-icon-book',
                'class'       => 'Lovata\Shopaholic\Models\Settings',
                'order'       => 100
            ]
        ];
    }

    /**
     * Plugin boot method
     */
    public function boot()
    {
        $this->addSettingFields();
    }

    /**
     * Add addition fields to "Setting" model
     */
    protected function addSettingFields()
    {
        // Extend "Shopaholic" settings form, add filter settings
        Event::listen('backend.form.extendFields', function($obWidget) {

            /**@var \Backend\Widgets\Form $obWidget */
            // Only for the Settings controller
            if (!$obWidget->getController() instanceof \System\Controllers\Settings) {
                return;
            }

            // Only for the Settings model
            if (!$obWidget->model instanceof Settings) {
                return;
            }

            $arFields = Settings::getAdditionFields();
            if(empty($arFields)) {
                return;
            }

            //Add addition field
            $obWidget->addTabFields($arFields);
        });
    }
}
