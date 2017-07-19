<?php namespace Lovata\Shopaholic\Classes\Store;

use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Plugin;

/**
 * Class BrandListStore
 * @package Lovata\Shopaholic\Classes\Store
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class BrandListStore
{
    const CACHE_TAG_LIST = 'shopaholic-brand-list';

    /**
     * Get cached brand ID list, filter by category ID
     * @param int $iCategoryID
     * @return array|null
     */
    public function getByCategory($iCategoryID)
    {
        if(empty($iCategoryID)) {
            return null;
        }
        
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_LIST, CategoryItem::CACHE_TAG_ELEMENT];
        $sCacheKey = $iCategoryID;

        $arBrandIDList = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arBrandIDList)) {
            return $arBrandIDList;
        }

        //Get brand ID list
        /** @var array $arBrandIDList */
        $arBrandIDList = Product::getByCategory($iCategoryID)->where('brand_id', '>', 0)->groupBy('brand_id')->lists('brand_id');

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arBrandIDList);

        return $arBrandIDList;
    }

    /**
     * Clear brand ID list by product category ID
     * @param int $iCategoryID
     */
    public function clearListByCategory($iCategoryID)
    {
        if(empty($iCategoryID)) {
            return;
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_LIST, CategoryItem::CACHE_TAG_ELEMENT];
        $sCacheKey = $iCategoryID;
        
        CCache::clear($arCacheTags, $sCacheKey);
    }

    /**
     * Get active brand ID list
     * @return array|null
     */
    public function getActiveList()
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_LIST];
        $sCacheKey = self::CACHE_TAG_LIST;

        $arBrandIDList = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arBrandIDList)) {
            return $arBrandIDList;
        }

        //Get brand ID list
        /** @var array $arBrandIDList */
        $arBrandIDList = Brand::active()->lists('id');
        if(empty($arBrandIDList)) {
            return null;
        }
        
        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arBrandIDList);

        return $arBrandIDList;
    }
}