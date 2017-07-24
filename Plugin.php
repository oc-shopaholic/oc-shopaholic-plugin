<?php namespace Lovata\Shopaholic;

use Event;
use System\Classes\PluginBase;

use Lovata\Shopaholic\Classes\Helper\PriceHelper;

use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Item\ProductItem;

use Lovata\Shopaholic\Classes\Collection\CategoryCollection;
use Lovata\Shopaholic\Classes\Collection\ProductCollection;

use Lovata\Shopaholic\Classes\Store\CategoryListStore;
use Lovata\Shopaholic\Classes\Store\ProductListStore;

use Lovata\Shopaholic\Classes\Event\BrandModelHandler;
use Lovata\Shopaholic\Classes\Event\CategoryModelHandler;
use Lovata\Shopaholic\Classes\Event\OfferModelHandler;
use Lovata\Shopaholic\Classes\Event\ProductModelHandler;
use Lovata\Shopaholic\Classes\Event\SettingsModelHandler;

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
                'order'       => 100
            ]
        ];
    }

    /**
     * Plugin boot method
     */
    public function boot()
    {
        $this->app->singleton(PriceHelper::class, PriceHelper::class);

        $this->app->bind(BrandItem::class, BrandItem::class);
        $this->app->bind(CategoryItem::class, CategoryItem::class);
        $this->app->bind(OfferItem::class, OfferItem::class);
        $this->app->bind(ProductItem::class, ProductItem::class);

        $this->app->bind(ProductCollection::class, ProductCollection::class);
        $this->app->bind(CategoryCollection::class, CategoryCollection::class);

        $this->app->singleton(ProductListStore::class, ProductListStore::class);
        $this->app->singleton(CategoryListStore::class, CategoryListStore::class);

        $this->app->singleton(CategoryModelHandler::class, CategoryModelHandler::class);
        $this->app->singleton(OfferModelHandler::class, OfferModelHandler::class);
        $this->app->singleton(ProductModelHandler::class, ProductModelHandler::class);
        $this->app->singleton(BrandModelHandler::class, BrandModelHandler::class);
        $this->app->singleton(SettingsModelHandler::class, SettingsModelHandler::class);

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
        Event::subscribe(SettingsModelHandler::class);
    }
}
