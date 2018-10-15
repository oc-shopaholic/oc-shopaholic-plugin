<?php namespace Lovata\Shopaholic;

use Event;
use System\Classes\PluginBase;

//Event list
use Lovata\Shopaholic\Classes\Event\ExtendMenuHandler;
//Brand events
use Lovata\Shopaholic\Classes\Event\Brand\BrandModelHandler;
//Category events
use Lovata\Shopaholic\Classes\Event\Category\CategoryModelHandler;
//Offer events
use Lovata\Shopaholic\Classes\Event\Offer\OfferModelHandler;
//Product events
use Lovata\Shopaholic\Classes\Event\Product\ProductModelHandler;
use Lovata\Shopaholic\Classes\Event\Product\ProductRelationHandler;
//Promo block events
use Lovata\Shopaholic\Classes\Event\PromoBlock\PromoBlockModelHandler;
use Lovata\Shopaholic\Classes\Event\PromoBlock\PromoBlockRelationHandler;

/**
 * Class Plugin
 * @package Lovata\Shopaholic
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
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
            'Lovata\Shopaholic\Components\CategoryList'   => 'CategoryList',
            'Lovata\Shopaholic\Components\CategoryPage'   => 'CategoryPage',
            'Lovata\Shopaholic\Components\CategoryData'   => 'CategoryData',
            'Lovata\Shopaholic\Components\Breadcrumbs'    => 'CatalogBreadcrumbs',
            'Lovata\Shopaholic\Components\ProductData'    => 'ProductData',
            'Lovata\Shopaholic\Components\ProductPage'    => 'ProductPage',
            'Lovata\Shopaholic\Components\ProductList'    => 'ProductList',
            'Lovata\Shopaholic\Components\BrandData'      => 'BrandData',
            'Lovata\Shopaholic\Components\BrandPage'      => 'BrandPage',
            'Lovata\Shopaholic\Components\BrandList'      => 'BrandList',
            'Lovata\Shopaholic\Components\PromoBlockData' => 'PromoBlockData',
            'Lovata\Shopaholic\Components\PromoBlockPage' => 'PromoBlockPage',
            'Lovata\Shopaholic\Components\PromoBlockList' => 'PromoBlockList',
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
        Event::subscribe(ExtendMenuHandler::class);
        //Brand events
        Event::subscribe(BrandModelHandler::class);
        //Category events
        Event::subscribe(CategoryModelHandler::class);
        //Offer events
        Event::subscribe(OfferModelHandler::class);
        //Product events
        Event::subscribe(ProductModelHandler::class);
        Event::subscribe(ProductRelationHandler::class);
        //Promo block events
        Event::subscribe(PromoBlockModelHandler::class);
        Event::subscribe(PromoBlockRelationHandler::class);
    }
}
