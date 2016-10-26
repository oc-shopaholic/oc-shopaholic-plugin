<?php namespace Lovata\Shopaholic\Components;

use Lang;
use Cms\Classes\ComponentBase;
use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\Settings;
use Lovata\Shopaholic\Plugin;
use System\Classes\PluginManager;

/**
 * Class Breadcrumbs
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Breadcrumbs extends ComponentBase
{
    const CACHE_TAG = 'shopaholic-breadcrumbs';

    public function componentDetails()
    {
        return [
            'name' => Lang::get('lovata.shopaholic::lang.component.breadcumps_name'),
            'description' => Lang::get('lovata.shopaholic::lang.component.breadcumps_description'),
        ];
    }

    /**
     * Get breadcrumps by category id
     * @param int $iCategoryID
     * @param int $iTagID
     * @return array
     */
    public function getByCategoryID($iCategoryID, $iTagID = null) {

        if(empty($iCategoryID)) {
            return [];
        }
        
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG, Category::CACHE_TAG_ELEMENT];
        $sCacheKey = implode('_', [$iCategoryID, $iTagID]);

        $arResult = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arResult)) {
            return $arResult;
        }

        $arResult = [];
        $bActiveCategory = true;
        
        //Apply tag filter
        if(PluginManager::instance()->hasPlugin('Lovata.TagsShopaholic') && !empty($iTagID)) {
            $arTagData = \Lovata\TagsShopaholic\Models\Tag::getCacheData($iTagID);
            if(!empty($arTagData)) {
                $arTagData['active'] = true;
                $arResult[] = $arTagData;
            }

            $bActiveCategory = false;
        }

        /** @var Category $obCategory */
        $obCategory = Category::find($iCategoryID);
        if(empty($obCategory)) {
            return $arResult;
        }

        //Get category data
        $this->getCategoryData($arResult, $obCategory, $bActiveCategory);
        $arResult = array_reverse($arResult);

        //Set cache data
        $iCacheTime = Settings::getCacheTime('cache_time_category');
        CCache::put($arCacheTags, $sCacheKey, $arResult, $iCacheTime);
        
        return $arResult;
    }


    /**
     * Get breadcrumps by product ID
     * @param int $iProductID
     * @param int $iCategoryID
     * @return array
     */
    public function getByProductID($iProductID, $iCategoryID = 0) {

        if(empty($iProductID)) {
            return [];
        }
        
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG, Product::CACHE_TAG_ELEMENT, Category::CACHE_TAG_ELEMENT.'_'.$iCategoryID];
        $sCacheKey = $iProductID;

        $arResult = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arResult)) {
            return $arResult;
        }

        $arResult = [];

        //Get product by slug
        /** @var Product $obProduct */
        $obProduct = Product::with('category')->active()->find($iProductID);
        if(empty($obProduct)) {
            return $arResult;
        }

        //Get product data
        $arResult[] = [
            'id' => $obProduct->id,
            'name' => $obProduct->name,
            'slug' => $obProduct->slug,
            'active' => true,
        ];

        //Get product category
        $obCategory = $obProduct->category;

        if(empty($obCategory)) {
            
            //Set cache data
            $iCacheTime = Settings::getCacheTime('cache_time_category');
            CCache::put($arCacheTags, $sCacheKey, $arResult, $iCacheTime);
            
            return $arResult;
        }

        //Get category data
        $this->getCategoryData($arResult, $obCategory);
        $arResult = array_reverse($arResult);

        //Set cache data
        $iCacheTime = Settings::getCacheTime('cache_time_category');
        CCache::put($arCacheTags, $sCacheKey, $arResult, $iCacheTime);

        return $arResult;
    }

    /**
     * Get category data
     * @param array $arResult
     * @param Category $obCategory
     * @param bool $bActiveCategory
     */
    protected function getCategoryData(&$arResult, $obCategory, $bActiveCategory = false) {

        if(empty($obCategory)) {
            return;
        }

        $arResult[] = [
            'id' => $obCategory->id,
            'name' => $obCategory->name,
            'slug' => $obCategory->slug,
            'slug_full' => Category::getFullSlug($obCategory->parent, $obCategory->slug),
            'active' => $bActiveCategory,
        ];

        /** @var Category $obParentCategory */
        $obParentCategory = $obCategory->parent;
        if(!empty($obParentCategory)) {
            $this->getCategoryData($arResult, $obParentCategory);
        }
    }
}