<?php namespace Lovata\Shopaholic\Classes\Event;

use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Classes\Store\ProductListStore;
use Lovata\Shopaholic\Controllers\Products;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\Settings;
use Lovata\Shopaholic\Plugin;
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

    public function __construct(ProductListStore $obProductListStore)
    {
        $this->obProductListStore = $obProductListStore;
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
        $this->clearItemCache();

        if($obElement->active) {
            $this->removeFromActiveList();
        }

        $this->removeFromCategoryList($obElement->category_id);
        $this->removeFromBrandList($obElement->brand_id);

        $this->removeFromSortingList(ProductListStore::SORT_PRICE_ASC);
        $this->removeFromSortingList(ProductListStore::SORT_PRICE_DESC);
        $this->removeFromSortingList(ProductListStore::SORT_NEW);
        $this->removeFromSortingList(ProductListStore::SORT_POPULARITY_DESC);
        $this->removeFromSortingList(ProductListStore::SORT_NO);
    }

    /**
     * Clear item cache
     */
    protected function clearItemCache()
    {
        ProductItem::clearCache($this->obElement->id);
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

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, ProductListStore::CACHE_TAG_LIST];
        $sCacheKey = ProductListStore::CACHE_TAG_LIST;

        //Clear cache data
        CCache::clear($arCacheTags, $sCacheKey);
        $this->obProductListStore->getActiveList();
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
        $this->addToCategoryList($this->obElement->category_id);
        $this->removeFromCategoryList((int) $this->obElement->getOriginal('category_id'));
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
        $this->addToBrandList($this->obElement->brand_id);
        $this->removeFromBrandList((int) $this->obElement->getOriginal('brand_id'));
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
     * Remove product from active product ID list
     */
    private function removeFromActiveList()
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, ProductListStore::CACHE_TAG_LIST];
        $sCacheKey = ProductListStore::CACHE_TAG_LIST;

        //Check cache array
        $arProductIDList = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arProductIDList)) {
            $this->obProductListStore->getActiveList();
            return;
        }

        if(!in_array($this->obElement->id, $arProductIDList)) {
            return;
        }

        //Remove element from cache array and save
        $iPosition = array_search($this->obElement->id, $arProductIDList);
        if($iPosition === false) {
            return;
        }
        
        unset($arProductIDList[$iPosition]);

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arProductIDList);
    }

    /**
     * Add product in product ID list for category
     * @param int $iCategoryID
     */
    private function addToCategoryList($iCategoryID)
    {
        if(empty($iCategoryID)) {
            return;
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, ProductListStore::CACHE_TAG_LIST, CategoryItem::CACHE_TAG_ELEMENT];
        $sCacheKey = $iCategoryID;

        //Check cache array
        $arProductIDList = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arProductIDList)) {
            $this->obProductListStore->getByCategory($iCategoryID);
            return;
        }

        if(in_array($this->obElement->id, $arProductIDList)) {
            return;
        }

        //Add element to cache array and save
        $arProductIDList[] = $this->obElement->id;

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arProductIDList);
    }

    /**
     * Remove product from product ID list for category
     * @param int $iCategoryID
     */
    private function removeFromCategoryList($iCategoryID)
    {
        if(empty($iCategoryID)) {
            return;
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, ProductListStore::CACHE_TAG_LIST, CategoryItem::CACHE_TAG_ELEMENT];
        $sCacheKey = $iCategoryID;

        //Check cache array
        $arProductIDList = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arProductIDList)) {
            $this->obProductListStore->getByCategory($iCategoryID);
            return;
        }

        if(!in_array($this->obElement->id, $arProductIDList)) {
            return;
        }

        //Remove element from cache array and save
        $iPosition = array_search($this->obElement->id, $arProductIDList);
        if($iPosition === false) {
            return;
        }

        unset($arProductIDList[$iPosition]);

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arProductIDList);
    }

    /**
     * Add product in product ID list for brand
     * @param int $iBrandID
     */
    private function addToBrandList($iBrandID)
    {
        if(empty($iBrandID)) {
            return;
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, ProductListStore::CACHE_TAG_LIST, BrandItem::CACHE_TAG_ELEMENT];
        $sCacheKey = $iBrandID;

        //Check cache array
        $arProductIDList = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arProductIDList)) {
            $this->obProductListStore->getByBrand($iBrandID);
            return;
        }

        if(in_array($this->obElement->id, $arProductIDList)) {
            return;
        }

        //Add element to cache array and save
        $arProductIDList[] = $this->obElement->id;

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arProductIDList);
    }

    /**
     * Remove product from product ID list for brand
     * @param int $iBrandID
     */
    private function removeFromBrandList($iBrandID)
    {
        if(empty($iBrandID)) {
            return;
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, ProductListStore::CACHE_TAG_LIST, BrandItem::CACHE_TAG_ELEMENT];
        $sCacheKey = $iBrandID;

        //Check cache array
        $arProductIDList = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arProductIDList)) {
            $this->obProductListStore->getByBrand($iBrandID);
            return;
        }

        if(!in_array($this->obElement->id, $arProductIDList)) {
            return;
        }

        //Remove element from cache array and save
        $iPosition = array_search($this->obElement->id, $arProductIDList);
        if($iPosition === false) {
            return;
        }

        unset($arProductIDList[$iPosition]);

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arProductIDList);
    }

    /**
     * Remove product from product ID list with sorting
     * @param string $sSorting
     */
    private function removeFromSortingList($sSorting)
    {
        if(empty($sSorting)) {
            return;
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, ProductListStore::CACHE_TAG_LIST];
        $sCacheKey = $sSorting;

        //Check cache array
        $arProductIDList = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arProductIDList)) {
            $this->obProductListStore->getBySorting($sSorting);
            return;
        }

        if(!in_array($this->obElement->id, $arProductIDList)) {
            return;
        }

        //Remove element from cache array and save
        $iPosition = array_search($this->obElement->id, $arProductIDList);
        if($iPosition === false) {
            return;
        }

        unset($arProductIDList[$iPosition]);

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arProductIDList);
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