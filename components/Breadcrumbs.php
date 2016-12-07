<?php namespace Lovata\Shopaholic\Components;

use Cms\Classes\ComponentBase;
use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Product;
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

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'          => 'lovata.shopaholic::lang.component.breadcrumbs_name',
            'description'   => 'lovata.shopaholic::lang.component.breadcrumbs_description',
        ];
    }

    /**
     * Get breadcrumbs by category id
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
        
        //Get tag element
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
        CCache::forever($arCacheTags, $sCacheKey, $arResult);
        
        return $arResult;
    }


    /**
     * Get breadcrumbs by product ID
     * @param int $iProductID
     * @return array
     */
    public function getByProductID($iProductID) {

        if(empty($iProductID)) {
            return [];
        }
        
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG, Product::CACHE_TAG_ELEMENT];
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
            CCache::forever($arCacheTags, $sCacheKey, $arResult);
            return $arResult;
        }

        //Get category data
        $this->getCategoryData($arResult, $obCategory);
        $arResult = array_reverse($arResult);

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arResult);

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
            'active' => $bActiveCategory,
        ];

        $obParentCategory = $obCategory->parent;
        if(!empty($obParentCategory)) {
            $this->getCategoryData($arResult, $obParentCategory);
        }
    }
}