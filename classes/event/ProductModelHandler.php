<?php namespace Lovata\Shopaholic\Classes\Event;

use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Classes\Store\BrandListStore;
use Lovata\Shopaholic\Classes\Store\ProductListStore;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\Settings;
use System\Classes\PluginManager;

/**
 * Class ProductModelHandler
 * @package Lovata\Shopaholic\Classes\Event
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductModelHandler
{
    /** @var  Product */
    protected $obElement;

    /** @var  ProductListStore */
    protected $obProductListStore;
    
    /** @var  BrandListStore */
    protected $obBrandListStore;

    /**
     * ProductModelHandler constructor.
     *
     * @param ProductListStore $obProductListStore
     * @param BrandListStore   $obBrandListStore
     */
    public function __construct(ProductListStore $obProductListStore, BrandListStore $obBrandListStore)
    {
        $this->obProductListStore = $obProductListStore;
        $this->obBrandListStore = $obBrandListStore;
    }

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     * 
     */
    public function subscribe($obEvent)
    {
        Product::extend(function ($obElement) {
            /** @var Product $obElement */
            $obElement->bindEvent('model.afterSave', function () use($obElement) {
                $this->afterSave($obElement);
            });
        });

        Product::extend(function ($obElement) {
            /** @var Product $obElement */
            $obElement->bindEvent('model.afterDelete', function () use($obElement) {
                $this->afterDelete($obElement);
            });
        });

        $obEvent->listen('backend.list.extendColumns', function ($obWidget) {
            $this->hideListColumns($obWidget);
        });

        $obEvent->listen('backend.form.extendFields', function ($obWidget) {
            $this->hideListColumns($obWidget);
        });
    }

    /**
     * After save event handler
     * @param Product $obElement
     */
    public function afterSave($obElement)
    {
        if(empty($obElement) || !$obElement instanceof Product) {
            return;
        }

        $this->obElement = $obElement;
        $this->clearItemCache();

        //Check "active" flag
        $this->checkActiveField();

        //Check "category_id" field
        $this->checkCategoryIDField();

        //Check "brand_id" field
        $this->checkBrandIDField();

        //Check "popularity" field
        $this->checkPopularityField();
    }

    /**
     * After delete event handler
     * @param Product $obElement
     */
    public function afterDelete($obElement)
    {
        if(empty($obElement) || !$obElement instanceof Product) {
            return;
        }

        $this->obElement = $obElement;
        $this->processOfferAfterDelete();
        $this->clearItemCache();

        if($obElement->active) {
            $this->obProductListStore->clearActiveList();
        }

        $this->obProductListStore->clearListByCategory($this->obElement->category_id);
        $this->obBrandListStore->clearListByCategory($this->obElement->category_id);

        $this->obProductListStore->clearListByBrand($obElement->brand_id);

        $this->obProductListStore->updateCacheBySorting(ProductListStore::SORT_PRICE_ASC);
        $this->obProductListStore->updateCacheBySorting(ProductListStore::SORT_PRICE_DESC);
        $this->obProductListStore->updateCacheBySorting(ProductListStore::SORT_NEW);
        $this->obProductListStore->updateCacheBySorting(ProductListStore::SORT_POPULARITY_DESC);
        $this->obProductListStore->updateCacheBySorting(ProductListStore::SORT_NO);
    }

    /**
     * Clear item cache
     */
    protected function clearItemCache()
    {
        ProductItem::clearCache($this->obElement->id);
    }

    /**
     * Set active = false in offer list, after product was removed
     */
    protected function processOfferAfterDelete()
    {
        $obOfferList = $this->obElement->offer;
        if($obOfferList->isEmpty()) {
            return;
        }

        foreach($obOfferList as $obOffer) {
            $obOffer->active = false;
            $obOffer->save();
        }
    }
    
    /**
     * Check product "active" field, if it was changed, then clear cache
     */
    private function checkActiveField()
    {
        //check product "active" field
        if($this->obElement->getOriginal('active') == $this->obElement->active) {
            return;
        }

        $this->obProductListStore->clearActiveList();
    }

    /**
     * Check product "category_id" field, if it was changed, then clear cache
     */
    private function checkCategoryIDField()
    {
        //Check "category_id" field
        if($this->obElement->getOriginal('category_id') == $this->obElement->category_id){
            return;
        }

        //Update product ID cache list for category
        $this->obProductListStore->clearListByCategory($this->obElement->category_id);
        $this->obProductListStore->clearListByCategory((int) $this->obElement->getOriginal('category_id'));
        
        $this->obBrandListStore->clearListByCategory($this->obElement->category_id);
        $this->obBrandListStore->clearListByCategory((int) $this->obElement->getOriginal('category_id'));
    }

    /**
     * Check product "brand_id" field, if it was changed, then clear cache
     */
    private function checkBrandIDField()
    {
        //Check "brand_id" field
        if($this->obElement->getOriginal('brand_id') == $this->obElement->brand_id){
            return;
        }

        //Update product ID cache list for brand
        $this->obProductListStore->clearListByBrand($this->obElement->brand_id);
        $this->obProductListStore->clearListByBrand((int) $this->obElement->getOriginal('brand_id'));
    }

    /**
     * Check product "popularity" field, if it was changed, then clear cache
     */
    private function checkPopularityField()
    {
        //Check "popularity" field
        $bNeedUpdateCache = PluginManager::instance()->hasPlugin('Lovata.PopularityShopaholic')
            && $this->obElement->getOriginal('popularity') != $this->obElement->popularity;

        if(!$bNeedUpdateCache) {
            return;
        }

        //Update product list with popularity
        $this->obProductListStore->updateCacheBySorting(ProductListStore::SORT_POPULARITY_DESC);
    }

    /**
     * Hide backend list columns
     * @param \Backend\Widgets\Lists|\Backend\Widgets\Form $obWidget
     */
    protected function hideListColumns($obWidget)
    {
        // Only for the Product model
        if (!$obWidget->model instanceof Product) {
            return;
        }

        $arConfiguredViewFields = self::getConfiguredBackendFields();
        if(empty($arConfiguredViewFields)) {
            return;
        }

        foreach($arConfiguredViewFields as $sFieldKey => $sFieldName) {
            if(!Settings::getValue('product_'.$sFieldKey)) {
                continue;
            }

            if($obWidget instanceof \Backend\Widgets\Lists) {
                $obWidget->removeColumn($sFieldKey);
            } else {
                $obWidget->removeField($sFieldKey);
            }
        }
    }

    /**
     * Get fields list for backend interface with switching visibility
     * @return array
     */
    public static function getConfiguredBackendFields()
    {
        return [
            'code'                  => 'lovata.toolbox::lang.field.code',
            'external_id'           => 'lovata.toolbox::lang.field.external_id',
            'category'              => 'lovata.toolbox::lang.field.category',
            'brand'                 => 'lovata.shopaholic::lang.field.brand',
            'preview_text'          => 'lovata.toolbox::lang.field.preview_text',
            'description'           => 'lovata.toolbox::lang.field.description',
            'preview_image'         => 'lovata.toolbox::lang.field.preview_image',
            'images'                => 'lovata.toolbox::lang.field.images',
        ];
    }
}