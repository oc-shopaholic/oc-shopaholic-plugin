<?php namespace Lovata\Shopaholic\Classes\Event;

use System\Classes\PluginManager;

use Lovata\Toolbox\Classes\Event\ModelHandler;

use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Store\BrandListStore;
use Lovata\Shopaholic\Classes\Store\ProductListStore;

/**
 * Class ProductModelHandler
 * @package Lovata\Shopaholic\Classes\Event
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductModelHandler extends ModelHandler
{
    /** @var  Product */
    protected $obElement;

    /** @var  ProductListStore */
    protected $obListStore;
    
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
        $this->obListStore = $obProductListStore;
        $this->obBrandListStore = $obBrandListStore;
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return Product::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return ProductItem::class;
    }

    /**
     * After save event handler
     */
    protected function afterSave()
    {
        parent::afterSave();

        //Check "category_id" field
        $this->checkCategoryIDField();

        //Check "brand_id" field
        $this->checkBrandIDField();

        //Check "popularity" field
        $this->checkPopularityField();
    }

    /**
     * After delete event handler
     */
    public function afterDelete()
    {
        $this->processOfferAfterDelete();
        parent::afterDelete();

        $this->obListStore->clearListByCategory($this->obElement->category_id);
        $this->obBrandListStore->clearListByCategory($this->obElement->category_id);

        $this->obListStore->clearListByBrand($this->obElement->brand_id);

        $this->obListStore->updateCacheBySorting(ProductListStore::SORT_PRICE_ASC);
        $this->obListStore->updateCacheBySorting(ProductListStore::SORT_PRICE_DESC);
        $this->obListStore->updateCacheBySorting(ProductListStore::SORT_NEW);
        $this->obListStore->updateCacheBySorting(ProductListStore::SORT_POPULARITY_DESC);
        $this->obListStore->updateCacheBySorting(ProductListStore::SORT_NO);
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
     * Check product "category_id" field, if it was changed, then clear cache
     */
    private function checkCategoryIDField()
    {
        //Check "category_id" field
        if($this->obElement->getOriginal('category_id') == $this->obElement->category_id){
            return;
        }

        //Update product ID cache list for category
        $this->obListStore->clearListByCategory($this->obElement->category_id);
        $this->obListStore->clearListByCategory((int) $this->obElement->getOriginal('category_id'));
        
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
        $this->obListStore->clearListByBrand($this->obElement->brand_id);
        $this->obListStore->clearListByBrand((int) $this->obElement->getOriginal('brand_id'));
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
        $this->obListStore->updateCacheBySorting(ProductListStore::SORT_POPULARITY_DESC);
    }

    /**
     * Check offer "active" field, if it was changed, then clear cache
     */
    protected function checkActiveField()
    {
        //check product "active" field
        if($this->obElement->getOriginal('active') == $this->obElement->active) {
            return;
        }

        $this->obListStore->clearActiveList();
        
        $obCategoryItem = CategoryItem::make($this->obElement->category_id);
        if($obCategoryItem->isEmpty()) {
            return;
        }
        
        $obCategoryItem->clearProductCount();
    }
}