<?php namespace Lovata\Shopaholic\Classes;

use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\Settings;
use Lovata\Shopaholic\Plugin;
use System\Classes\PluginManager;

/**
 * Class ProductListStore
 * @package Lovata\Shopaholic\Classes
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductListStore
{
    const SORT_NO = 'no';
    const SORT_PRICE_ASC = 'price|asc';
    const SORT_PRICE_DESC = 'price|desc';
    const SORT_NEW = 'new';
    const SORT_POPULARITY_DESC = 'popularity|desc';
    const SORT_CUSTOM = 'custom';

    /**
     * Get available sorting value list
     * @return array
     */
    public static function getAvailableSorting()
    {
        return [
            self::SORT_NO,
            self::SORT_PRICE_ASC,
            self::SORT_PRICE_DESC,
            self::SORT_NEW,
            self::SORT_POPULARITY_DESC,
            self::SORT_CUSTOM
        ];
    }

    /**
     * Get product ID list by sorting value
     * @param string $sSorting
     * @return array
     */
    public static function getBySorting($sSorting)
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST];
        $sCacheKey = $sSorting;

        $arProductIDList = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arProductIDList)) {
            return $arProductIDList;
        }

        return self::_updateCacheBySorting($sSorting);
    }

    /**
     * Update cache product ID list by sorting 
     * @param string $sSorting
     * @return array|null
     */
    private static function _updateCacheBySorting($sSorting)
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST];
        $sCacheKey = $sSorting;

        switch($sSorting) {
            case self::SORT_PRICE_ASC :

                //Get product ID list (sort by offer price)
                //We can not use groupBy() in this place
                /** @var array $arProductIDList */
                $arProductIDList = Offer::active()->orderBy('price', 'asc')->lists('product_id');
                if(empty($arProductIDList)) {
                    return null;
                }

                $arProductIDList = array_unique($arProductIDList);

                //Apply filter by product active
                $arProductIDList = self::applyActiveFilter($arProductIDList);

                break;
            case self::SORT_PRICE_DESC :

                //Get product ID list (sort by offer price)
                //We can not use groupBy() in this place
                /** @var array $arProductIDList */
                $arProductIDList = Offer::active()->orderBy('price', 'desc')->lists('product_id');
                if(empty($arProductIDList)) {
                    return null;
                }

                $arProductIDList = array_unique($arProductIDList);

                //Apply filter by product active
                $arProductIDList = self::applyActiveFilter($arProductIDList);

                break;
            case self::SORT_POPULARITY_DESC :

                if(!PluginManager::instance()->hasPlugin('Lovata.PopularityShopaholic')) {
                    return self::getActiveList();
                }

                /** @var array $arProductIDList */
                $arProductIDList = Product::orderBy('popularity', 'desc')->lists('id');
                if(empty($arProductIDList)) {
                    return null;
                }

                //Apply filter by product active
                $arProductIDList = self::applyActiveFilter($arProductIDList);

                //Set cache data
                $iCacheTime = 1440;
                CCache::put($arCacheTags, $sCacheKey, $arProductIDList, $iCacheTime);
                return $arProductIDList;
            case self::SORT_NEW :
                $arProductIDList = self::getActiveList();
                if(!empty($arProductIDList)) {
                    $arProductIDList = array_reverse($arProductIDList);
                }
                break;
            default:
                $arProductIDList = self::getActiveList();
                break;
        }

        if(empty($arProductIDList)) {
            return null;
        }

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arProductIDList);

        return $arProductIDList;
    }

    /**
     * Get cached product ID list by category ID
     * @param int $iCategoryID
     * @return array|null
     */
    public static function getByCategory($iCategoryID)
    {
        if(empty($iCategoryID)) {
            return null;
        }
        
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST, Category::CACHE_TAG_ELEMENT];
        $sCacheKey = $iCategoryID;

        $arProductIDList = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arProductIDList)) {
            return $arProductIDList;
        }

        //Get product ID list
        /** @var array $arProductIDList */
        $arProductIDList = Product::active()->getByCategory($iCategoryID)->lists('id');

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arProductIDList);

        return $arProductIDList;
    }

    /**
     * Get cached product ID list by category ID
     * @param string $sSorting
     * @param int $iCategoryID
     * @return array|null
     */
    public static function getSortingByCategory($sSorting, $iCategoryID)
    {
        if(empty($iCategoryID)) {
            return null;
        }
        
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST, Category::CACHE_TAG_ELEMENT];
        $sCacheKey = implode('_', [$sSorting, $iCategoryID]);

        $arProductIDList = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arProductIDList)) {
            return $arProductIDList;
        }

        //Get product ID list by sorting
        $arProductIDList = self::getBySorting($sSorting);
        if(empty($arProductIDList)) {
            return null;
        }
        
        //Get product ID list by category ID
        $arProductIDListByCategory = self::getByCategory($iCategoryID);
        if(empty($arProductIDListByCategory)) {
            return null;
        }
        
        $arProductIDList = array_intersect($arProductIDList, $arProductIDListByCategory);
        if(empty($arProductIDList)) {
            return null;
        }

        //Set cache data
        $iCacheTime = 1440;
        CCache::put($arCacheTags, $sCacheKey, $arProductIDList, $iCacheTime);

        return $arProductIDList;
    }

    /**
     * Get active product ID list
     * @return array|null
     */
    public static function getActiveList()
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST];
        $sCacheKey = Product::CACHE_TAG_LIST;

        $arProductIDList = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arProductIDList)) {
            return $arProductIDList;
        }

        //Get product ID list
        /** @var array $arProductIDList */
        $arProductIDList = Product::active()->lists('id');
        if(empty($arProductIDList)) {
            return null;
        }

        //Check active offers
        if(Settings::getValue('check_offer_active')) {

            //Get product list with active offers
            /** @var array $arProductIDListWithOffers */
            $arProductIDListWithOffers = Offer::active()->groupBy('product_id')->lists('product_id');
            if(empty($arProductIDListWithOffers)) {
                return null;
            }

            $arProductIDList = array_intersect($arProductIDList, $arProductIDListWithOffers);
            if(empty($arProductIDList)) {
                return null;
            }
        }

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arProductIDList);

        return $arProductIDList;
    }

    /**
     * Apply filter by product active
     * @param array $arProductIDList
     * @return array|null
     */
    public static function applyActiveFilter($arProductIDList)
    {
        if(empty($arProductIDList)) {
            return null;
        }

        //Get active product list
        $arActiveProductList = self::getActiveList();
        if(empty($arActiveProductList)) {
            return null;
        }

        return array_intersect($arProductIDList, $arActiveProductList);
    }

    /**
     * Update cache product lists after save
     * @param Product $obProduct
     */
    public static function updateCacheAfterSave($obProduct)
    {
        if(empty($obProduct) || !$obProduct instanceof Product) {
            return;
        }

        //Check "active" flag
        self::_checkChangeProductActive($obProduct);

        //Check "category_id" field
        self::_checkChangeProductCategory($obProduct);

        //Check "popularity" field
        self::_checkChangeProductPopularity($obProduct);
    }

    /**
     * Update cache product lists after delete
     * @param Product $obProduct
     */
    public static function updateCacheAfterDelete($obProduct)
    {
        if(empty($obProduct) || !$obProduct instanceof Product) {
            return;
        }
        
        if(!$obProduct->active) {
            return;
        }

        self::_removeFromActiveList($obProduct);
        self::_removeFromCategoryList($obProduct, $obProduct->category_id);

        self::_removeFromSortingList($obProduct, self::SORT_PRICE_ASC);
        self::_removeFromSortingList($obProduct, self::SORT_PRICE_DESC);
        self::_removeFromSortingList($obProduct, self::SORT_NEW);
        self::_removeFromSortingList($obProduct, self::SORT_POPULARITY_DESC);
        self::_removeFromSortingList($obProduct, self::SORT_NO);

        self::_clearSortingListByCategory($obProduct->category_id);
    }

    /**
     * Update cache product lists after save
     * @param Offer $obOffer
     */
    public static function updateCacheAfterOfferSave($obOffer)
    {
        if(empty($obOffer) || !$obOffer instanceof Offer) {
            return;
        }

        //Get product object
        $obProduct = $obOffer->product;
        if(empty($obProduct)) {
            return;
        }

        $bCacheClear = $obProduct->active && (
            $obOffer->getOriginal('active') != $obOffer->active
            || $obOffer->getOriginal('price') != $obOffer->price
        );

        if($bCacheClear) {
            return;
        }

        self::_updateCacheBySorting(self::SORT_PRICE_ASC);
        self::_updateCacheBySorting(self::SORT_PRICE_DESC);

        self::_clearSortingListByCategory($obProduct->category_id);
    }

    /**
     * Update cache product lists after offer delete
     * @param Offer $obOffer
     */
    public static function updateCacheAfterOfferDelete($obOffer)
    {
        if(empty($obOffer) || !$obOffer instanceof Offer) {
            return;
        }

        //Get product object
        $obProduct = $obOffer->product;
        if(empty($obProduct)) {
            return;
        }
        
        if(!$obOffer->active || !$obProduct->active) {
            return;
        }

        self::_updateCacheBySorting(self::SORT_PRICE_ASC);
        self::_updateCacheBySorting(self::SORT_PRICE_DESC);

        self::_clearSortingListByCategory($obProduct->category_id);
    }

    /**
     * Check product "active" flag, if it was changed, then clear cache
     * @param Product $obProduct
     */
    private static function _checkChangeProductActive($obProduct)
    {
        //check product "active" flag
        if($obProduct->getOriginal('active') == $obProduct->active) {
            return;
        }

        if($obProduct->active) {
            self::_addActiveProductToCache($obProduct);
            return;
        }

        self::_removeNotActiveProductFromCache($obProduct);
    }

    /**
     * Add active product to cache list (not active -> active)
     * @param Product $obProduct
     */
    private static function _addActiveProductToCache($obProduct)
    {
        //Update active product ID list
        self::_updateActiveList();

        //Update active product ID list by category filter
        self::_addToCategoryList($obProduct, $obProduct->category_id);
        self::_clearSortingListByCategory($obProduct->category_id);

        //Update active product ID list with sorting
        self::_updateCacheBySorting(self::SORT_PRICE_ASC);
        self::_updateCacheBySorting(self::SORT_PRICE_DESC);
        self::_updateCacheBySorting(self::SORT_NEW);
        self::_updateCacheBySorting(self::SORT_NO);

        if(PluginManager::instance()->hasPlugin('Lovata.PopularityShopaholic')) {
            self::_updateCacheBySorting(self::SORT_POPULARITY_DESC);
        }
    }

    /**
     * Remove not active product from cache (active -> not active)
     * @param Product $obProduct
     */
    private function _removeNotActiveProductFromCache($obProduct)
    {
        //Update active product ID list
        self::_removeFromActiveList($obProduct);

        //Update active product ID list by category filter
        if($obProduct->getOriginal('category_id') != $obProduct->category_id) {
            self::_removeFromCategoryList($obProduct, (int) $obProduct->getOriginal('category_id'));
            self::_clearSortingListByCategory((int) $obProduct->getOriginal('category_id'));
        } else {
            self::_removeFromCategoryList($obProduct, $obProduct->category_id);
            self::_clearSortingListByCategory($obProduct->category_id);
        }

        //Update active product ID list with sorting
        self::_removeFromSortingList($obProduct, self::SORT_PRICE_ASC);
        self::_removeFromSortingList($obProduct, self::SORT_PRICE_DESC);
        self::_removeFromSortingList($obProduct, self::SORT_NEW);
        self::_removeFromSortingList($obProduct, self::SORT_NO);

        if(PluginManager::instance()->hasPlugin('Lovata.PopularityShopaholic')) {
            self::_removeFromSortingList($obProduct, self::SORT_POPULARITY_DESC);
        }
    }

    /**
     * Check product "category_id" field, if it was changed, then clear cache
     * @param Product $obProduct
     */
    private static function _checkChangeProductCategory($obProduct)
    {
        //Check "category_id" field
        $bClearCache = $obProduct->getOriginal('category_id') != $obProduct->category_id
            && $obProduct->getOriginal('active') == $obProduct->active
            && $obProduct->active;

        if(!$bClearCache) {
            return;
        }

        //Update product cache list with category
        self::_addToCategoryList($obProduct, $obProduct->category_id);
        self::_removeFromCategoryList($obProduct, (int) $obProduct->getOriginal('category_id'));

        self::_clearSortingListByCategory($obProduct->category_id);
        self::_clearSortingListByCategory((int) $obProduct->getOriginal('category_id'));
    }

    /**
     * Check product "popularity" field, if it was changed, then clear cache
     * @param Product $obProduct
     */
    private static function _checkChangeProductPopularity($obProduct)
    {
        //Check "popularity" flag
        $bCacheClear = $obProduct->active
            && PluginManager::instance()->hasPlugin('Lovata.PopularityShopaholic')
            && $obProduct->getOriginal('popularity') != $obProduct->popularity;

        if(!$bCacheClear) {
            return;
        }

        //Update product list with popularity
        self::_updateCacheBySorting(self::SORT_POPULARITY_DESC);

        self::_clearSortingListByCategory($obProduct->category_id);
        if($obProduct->getOriginal('category_id') != $obProduct->category_id) {
            self::_clearSortingListByCategory((int) $obProduct->getOriginal('category_id'));
        }
    }

    /**
     * Update product in active product ID list
     */
    private static function _updateActiveList()
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST];
        $sCacheKey = Product::CACHE_TAG_LIST;

        //Clear cache data
        CCache::clear($arCacheTags, $sCacheKey);
        self::getActiveList();
    }

    /**
     * Remove product from active product ID list
     * @param Product $obProduct
     */
    private static function _removeFromActiveList($obProduct)
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST];
        $sCacheKey = Product::CACHE_TAG_LIST;

        //Check cache array
        $arProductIDList = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arProductIDList)) {
            self::getActiveList();
            return;
        }

        if(!in_array($obProduct->id, $arProductIDList)) {
            return;
        }

        //Remove element from cache array and save
        $iPosition = array_search($obProduct->id, $arProductIDList);
        if($iPosition === false) {
            return;
        }
        
        unset($arProductIDList[$iPosition]);

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arProductIDList);
    }

    /**
     * Add product in active product ID list by category filter
     * @param Product $obProduct
     * @param int $iCategoryID
     */
    private static function _addToCategoryList($obProduct, $iCategoryID)
    {
        if(empty($iCategoryID)) {
            return;
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST, Category::CACHE_TAG_ELEMENT];
        $sCacheKey = $iCategoryID;

        //Check cache array
        $arProductIDList = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arProductIDList)) {
            self::getByCategory($iCategoryID);
            return;
        }

        if(in_array($obProduct->id, $arProductIDList)) {
            return;
        }

        //Add element to cache array and save
        $arProductIDList[] = $obProduct->id;

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arProductIDList);
    }

    /**
     * Remove product from active product ID list by category filter
     * @param Product $obProduct
     * @param int $iCategoryID
     */
    private static function _removeFromCategoryList($obProduct, $iCategoryID)
    {
        if(empty($iCategoryID)) {
            return;
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST, Category::CACHE_TAG_ELEMENT];
        $sCacheKey = $iCategoryID;

        //Check cache array
        $arProductIDList = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arProductIDList)) {
            self::getByCategory($iCategoryID);
            return;
        }

        if(!in_array($obProduct->id, $arProductIDList)) {
            return;
        }

        //Remove element from cache array and save
        $iPosition = array_search($obProduct->id, $arProductIDList);
        if($iPosition === false) {
            return;
        }

        unset($arProductIDList[$iPosition]);

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arProductIDList);
    }

    /**
     * Remove product from active product ID list by sorting
     * @param Product $obProduct
     * @param string $sSorting
     */
    private static function _removeFromSortingList($obProduct, $sSorting)
    {
        if(empty($sSorting)) {
            return;
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST];
        $sCacheKey = $sSorting;

        //Check cache array
        $arProductIDList = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arProductIDList)) {
            self::getBySorting($sSorting);
            return;
        }

        if(!in_array($obProduct->id, $arProductIDList)) {
            return;
        }

        //Remove element from cache array and save
        $iPosition = array_search($obProduct->id, $arProductIDList);
        if($iPosition === false) {
            return;
        }

        unset($arProductIDList[$iPosition]);

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arProductIDList);
    }

    /**
     * Clear sorting product ID list by category ID
     * @param int $iCategoryID
     */
    private static function _clearSortingListByCategory($iCategoryID)
    {
        if(empty($iCategoryID)) {
            return;
        }
        
        $arSortingList = self::getAvailableSorting();
        if(empty($arSortingList)) {
            return;
        }
        
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST, Category::CACHE_TAG_ELEMENT];
        
        foreach($arSortingList as $sSorting) {
            $sCacheKey = implode('_', [$sSorting, $iCategoryID]);
            CCache::clear($arCacheTags, $sCacheKey);
        }
    }
} 