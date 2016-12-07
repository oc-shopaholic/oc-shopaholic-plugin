<?php namespace Lovata\Shopaholic;

use Event;
use Lovata\Shopaholic\Classes\ProductListStore;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;
use System\Classes\PluginBase;

/**
 * Class Plugin
 * @package Lovata\Shopaholic
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * @author Denis Plisko, d.plisko@lovata.com, LOVATA Group
 */
class Plugin extends PluginBase
{

    const NAME = 'shopaholic';
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
            'Lovata\Shopaholic\Components\Currency' => 'Currency',
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

    public function boot()
    {
        $this->eventUpdateProduct();
        $this->eventDeleteProduct();

        $this->eventUpdateOffer();
        $this->eventDeleteOffer();
    }

    /**
     * Update active property value
     */
    protected function eventUpdateProduct() {

        Event::listen(Product::CACHE_TAG_ELEMENT.'.after.save', function($obProduct) {

            if(empty($obProduct) || !$obProduct instanceof Product) {
                return;
            }
            
            ProductListStore::updateCacheAfterSave($obProduct);
        });
    }

    /**
     * Delete property value
     */
    protected function eventDeleteProduct() {

        Event::listen(Product::CACHE_TAG_ELEMENT.'.after.delete', function($obProduct) {

            if(empty($obProduct) || !$obProduct instanceof Product) {
                return;
            }
            
            ProductListStore::updateCacheAfterDelete($obProduct);
        });
    }

    /**
     * Update active property value
     */
    protected function eventUpdateOffer() {

        Event::listen(Offer::CACHE_TAG_ELEMENT.'.after.save', function($obOffer) {

            if(empty($obOffer) || !$obOffer instanceof Offer) {
                return;
            }

            ProductListStore::updateCacheAfterOfferSave($obOffer);
        });
    }

    /**
     * Delete property value
     */
    protected function eventDeleteOffer() {

        Event::listen(Offer::CACHE_TAG_ELEMENT.'.after.delete', function($obOffer) {

            if(empty($obOffer) || !$obOffer instanceof Offer) {
                return;
            }
            
            ProductListStore::updateCacheAfterOfferDelete($obOffer);
        });
    }
}
