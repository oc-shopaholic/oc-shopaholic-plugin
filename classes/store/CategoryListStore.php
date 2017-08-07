<?php namespace Lovata\Shopaholic\Classes\Store;

use Kharanenka\Helper\CCache;

use Lovata\Shopaholic\Plugin;
use Lovata\Shopaholic\Models\Category;

/**
 * Class CategoryListStore
 * @package Lovata\Shopaholic\Classes\Store
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CategoryListStore
{
    const CACHE_TAG_LIST = 'shopaholic-category-list';
    const CACHE_KEY_TOP_LEVEL_LIST = 'shopaholic-category-top-level-list';

    /**
     * Get top level category ID list
     * @return array|null
     */
    public function getTopLevelList()
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_LIST];
        $sCacheKey = self::CACHE_KEY_TOP_LEVEL_LIST;

        $arCategoryListID = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arCategoryListID)) {
            return $arCategoryListID;
        }

        /** @var array $arCategoryListID */
        $arCategoryListID = Category::active()
            ->where('nest_depth', 0)
            ->orderBy('nest_left', 'asc')
            ->lists('id');

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arCategoryListID);

        return $arCategoryListID;
    }

    /**
     * Clear top level category ID list
     */
    public function clearTopLevelList()
    {
        $arCacheTags = [Plugin::CACHE_TAG, CategoryListStore::CACHE_TAG_LIST];
        $sCacheKey = CategoryListStore::CACHE_KEY_TOP_LEVEL_LIST;
        
        CCache::clear($arCacheTags, $sCacheKey);
    }
}