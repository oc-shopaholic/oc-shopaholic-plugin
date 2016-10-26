<?php namespace Lovata\Shopaholic\Components;

use Lang;
use Cms\Classes\ComponentBase;
use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Settings;
use October\Rain\Database\Collection;
use Lovata\Shopaholic\Plugin;

/**
 * Class CategoryList
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CategoryList extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name' => Lang::get('lovata.shopaholic::lang.component.category_list_name'),
            'description' => Lang::get('lovata.shopaholic::lang.component.category_list_description'),
        ];
    }

    /**
     * Get category tree
     * @return array
     */
    public function getTree() {

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, Category::CACHE_TAG_LIST];
        $sCacheKey = Category::CACHE_TAG_LIST;

        $arResult = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arResult)) {
            return $arResult;
        }

        $arResult = [];
        
        /** @var Collection|Category[] $arCategories */
        $arCategories = Category::active()->orderBy('nest_left', 'asc')->get()->toNested();
        if($arCategories->isEmpty()) {
            return $arResult;
        }
        
        foreach($arCategories as $obCategory) {
            $arResult[$obCategory->id] = $obCategory->getData();
        }

        //Set cache data
        $iCacheTime = Settings::getCacheTime('cache_time_category');
        CCache::put($arCacheTags, $sCacheKey, $arResult, $iCacheTime);
        return $arResult;
    }
}