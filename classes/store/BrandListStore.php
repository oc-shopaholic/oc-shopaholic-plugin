<?php namespace Lovata\Shopaholic\Classes\Store;

use Kharanenka\Helper\CCache;

use Lovata\Toolbox\Traits\Store\TraitActiveList;

use Lovata\Shopaholic\Plugin;
use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Item\CategoryItem;

/**
 * Class BrandListStore
 * @package Lovata\Shopaholic\Classes\Store
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class BrandListStore
{
    use TraitActiveList;
    
    const CACHE_TAG_LIST = 'shopaholic-brand-list';

    /** @var ProductListStore */
    protected $obProductListStore;
    
    /**
     * BrandListStore constructor.
     *
     * @param ProductListStore $obProductListStore
     */
    public function __construct(ProductListStore $obProductListStore)
    {
        $this->obProductListStore = $obProductListStore;
    }

    /**
     * Get brand ID list with sorting
     * @return array
     */
    public function getBySorting()
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_LIST];
        $sCacheKey = 'sorting';

        $arBrandIDList = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arBrandIDList)) {
            return $arBrandIDList;
        }

        //Get brand ID list with sorting by sort_order field
        /** @var array $arBrandIDList */
        $arBrandIDList = Brand::orderBy('sort_order', 'asc')->lists('id');

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arBrandIDList);

        return $arBrandIDList;
    }

    /**
     * Clear sorting list
     */
    public function clearSortingList()
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_LIST];
        $sCacheKey = 'sorting';

        //Clear cache data
        CCache::clear($arCacheTags, $sCacheKey);
        $this->getBySorting();
    }
    
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
        if(empty($arBrandIDList)) {
            //Get brand ID list
            /** @var array $arBrandIDList */
            $arBrandIDList = Product::getByCategory($iCategoryID)
                ->where('brand_id', '>', 0)
                ->lists('brand_id', 'id');

            //Set cache data
            CCache::forever($arCacheTags, $sCacheKey, $arBrandIDList);
        }

        //Get active product list
        $arActiveProductIDList = $this->obProductListStore->getActiveList();
        if(empty($arActiveProductIDList) || empty($arBrandIDList)) {
            return null;
        }
        
        $arResult = [];
        foreach($arBrandIDList as $iProductID => $iBrandID) {
            if(!in_array($iProductID, $arActiveProductIDList)) {
                continue;
            }
            
            $arResult[] = $iBrandID;
        }
        
        $arResult = array_unique($arResult);

        return $arResult;
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
     * Get brand active ID list
     * @return array
     */
    protected function getActiveIDList()
    {
        /** @var array $arBrandIDList */
        $arBrandIDList = Brand::active()->lists('id');
        return $arBrandIDList;
    }
}