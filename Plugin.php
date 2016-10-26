<?php namespace Lovata\Shopaholic;

use System\Classes\PluginBase;

/**
 * Class Plugin
 * @package Lovata\Shopaholic
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * @author Denis Plisko, d.plisko@lovata.com, LOVATA Group
 */
class Plugin extends PluginBase
{

    const CACHE_TAG = 'shopaholic';
    const CACHE_TIME_DEFAULT = 10080;
    
    public function registerComponents()
    {
        return [
            'Lovata\Shopaholic\Components\ProductList' => 'ProductList',
            'Lovata\Shopaholic\Components\CategoryList' => 'CategoryList',
            'Lovata\Shopaholic\Components\CategoryPage' => 'CategoryPage',
            'Lovata\Shopaholic\Components\CategoryData' => 'CategoryData',
            'Lovata\Shopaholic\Components\Breadcrumbs' => 'CatalogBreadcrumbs',
            'Lovata\Shopaholic\Components\ProductData' => 'ProductData',
            'Lovata\Shopaholic\Components\ProductPage' => 'ProductPage',
        ];
    }

    public function registerSettings()
    {
        return [
            'config' => [
                'label'       => 'lovata.shopaholic::lang.plugin.name',
                'icon'        => 'icon-cogs',
                'description' => 'lovata.shopaholic::lang.plugin.description',
                'class'       => 'Lovata\Shopaholic\Models\Settings',
                'order'       => 100
            ]
        ];
    }
}
