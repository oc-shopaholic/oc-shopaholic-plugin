<?php namespace Lovata\Shopaholic\Components;

use Cms\Classes\ComponentBase;
use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Plugin;

/**
 * Class CategoryList
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CategoryList extends ComponentBase
{
    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'          => 'lovata.shopaholic::lang.component.category_list_name',
            'description'   => 'lovata.shopaholic::lang.component.category_list_description',
        ];
    }

    /**
     * Get category tree
     * @return array
     */
    public function get()
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, Category::CACHE_TAG_LIST];
        $sCacheKey = Category::CACHE_TAG_LIST;

        $arCategoryListID = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arCategoryListID)) {

            /** @var \October\Rain\Database\Collection|Category[] $arCategories */
            $arCategoryListID = Category::active()
                ->where('nest_depth', 0)
                ->orderBy('nest_left', 'asc')
                ->lists('id');

            //Set cache data
            CCache::forever($arCacheTags, $sCacheKey, $arCategoryListID);
        }

        if(empty($arCategoryListID)) {
            return [];
        }

        $arResult = [];
        foreach($arCategoryListID as $iCategoryID) {
            $arResult[$iCategoryID] = Category::getCacheData($iCategoryID);
        }

        return $arResult;
    }
}