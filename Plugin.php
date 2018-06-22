<?php namespace Lovata\Shopaholic;

use Event;
use System\Classes\PluginBase;

use Lovata\Shopaholic\Classes\Event\BrandModelHandler;
use Lovata\Shopaholic\Classes\Event\CategoryModelHandler;
use Lovata\Shopaholic\Classes\Event\OfferModelHandler;
use Lovata\Shopaholic\Classes\Event\ProductModelHandler;
use Lovata\Shopaholic\Classes\Event\ExtendMenuHandler;

/**
 * Class Plugin
 * @package Lovata\Shopaholic
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Plugin extends PluginBase
{
    /** @var array Plugin dependencies */
    public $require = ['Lovata.Toolbox'];

    /**
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Lovata\Shopaholic\Components\CategoryList' => 'CategoryList',
            'Lovata\Shopaholic\Components\CategoryPage' => 'CategoryPage',
            'Lovata\Shopaholic\Components\CategoryData' => 'CategoryData',
            'Lovata\Shopaholic\Components\Breadcrumbs'  => 'CatalogBreadcrumbs',
            'Lovata\Shopaholic\Components\ProductData'  => 'ProductData',
            'Lovata\Shopaholic\Components\ProductPage'  => 'ProductPage',
            'Lovata\Shopaholic\Components\ProductList'  => 'ProductList',
            'Lovata\Shopaholic\Components\BrandData'    => 'BrandData',
            'Lovata\Shopaholic\Components\BrandPage'    => 'BrandPage',
            'Lovata\Shopaholic\Components\BrandList'    => 'BrandList',
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
                'order'       => 100,
                'permissions' => [
                    'shopaholic-settings',
                ],
            ],
        ];
    }

    /**
     * Plugin boot method
     */
    public function boot()
    {
        $this->addEventListener();
    }

    /**
     * Add event listeners
     */
    protected function addEventListener()
    {
        Event::subscribe(CategoryModelHandler::class);
        Event::subscribe(OfferModelHandler::class);
        Event::subscribe(ProductModelHandler::class);
        Event::subscribe(BrandModelHandler::class);
        Event::subscribe(ExtendMenuHandler::class);
    }
}
