<?php namespace Lovata\Shopaholic\Components;

use Cms\Classes\ComponentBase;
use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Models\Category;
use October\Rain\Database\Collection;
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
    public function get() {

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
            $arResult[$obCategory->id] = Category::getCacheData($obCategory->id, $obCategory);
        }

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arResult);
        return $arResult;
    }
}