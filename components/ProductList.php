<?php namespace Lovata\Shopaholic\Components;

use Input;
use Lang;
use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Classes\Pagination;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\Settings;
use System\Classes\PluginManager;
use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Plugin;

/**
 * Class ProductList
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductList extends ComponentBase
{
    
    const SORT_NO = 'no';
    const SORT_PRICE_ASC = 'price|asc';
    const SORT_PRICE_DESC = 'price|desc';
    const SORT_NEW = 'new';
    const SORT_POPULARITY_DESC = 'popularity|desc';
    
    protected $iProductOnPage = 10;
    protected $arResult = [];
    protected $iProductId;
    
    public function componentDetails()
    {
        return [
            'name' => Lang::get('lovata.shopaholic::lang.component.product_list_name'),
            'description' => Lang::get('lovata.shopaholic::lang.component.product_list_description'),
        ];
    }

    public function defineProperties()
    {
        $arProperties = [
            'sorting' => [
                'title' => Lang::get('lovata.shopaholic::lang.component.product_list_sorting'),
                'type' => 'dropdown',
                'default' => self::SORT_NO,
                'options' => [
                    self::SORT_NO => Lang::get('lovata.shopaholic::lang.component.sorting_no'),
                    self::SORT_PRICE_ASC => Lang::get('lovata.shopaholic::lang.component.sorting_price_asc'),
                    self::SORT_PRICE_DESC => Lang::get('lovata.shopaholic::lang.component.sorting_price_desc'),
                    self::SORT_NEW => Lang::get('lovata.shopaholic::lang.component.sorting_new'),
                ],

            ],
        ];

        if(PluginManager::instance()->hasPlugin('Lovata.PopularityShopaholic')) {
            $arProperties['sorting']['options'][self::SORT_POPULARITY_DESC] = Lang::get('lovata.shopaholic::lang.component.sorting_popularity_desc');
        }
        
        $arProperties = array_merge($arProperties, Pagination::getProperties());
        
        return $arProperties;
    }
    
    public function onRun()
    {
        $iProductOnPage = $this->property('countPerPage');
        if($iProductOnPage > 0) {
            $this->iProductOnPage = $iProductOnPage;
        }

        if(!\Request::ajax()) {
            $this->addJs('/plugins/lovata/shopaholic/assets/js/main.js');
        }
    }

    /**
     * Get product list by page number
     * @param $sCategorySlug
     * @param int $iPage
     * @param string $sTag
     * @return array
     */
    public function get($sCategorySlug, $iPage = 1, $sTag = null) {
        
        $arResult = [
            'list' => [],
            'pagination' => [],
            'page' => $iPage,
            'count' => 0,
            'category_id' => null,
        ];
        
        if(empty($sCategorySlug)) {
            return $arResult;
        }

        $iRequestPage = Input::get('page');
        if(!empty($iRequestPage)) {
            $iPage = $iRequestPage;
        }

        //Set page default value
        if($iPage < 1 && $iPage != -1) {
            $iPage = 1;
            $arResult['page'] = $iPage;
        }
        
        if(isset($this->arResult[$sCategorySlug]) && isset($this->arResult[$sCategorySlug][$iPage])) {
            return $this->arResult[$sCategorySlug][$iPage];
        }
        
        //Get category object
        /** @var Category $obCategory */
        $obCategory = Category::active()->slug($sCategorySlug)->first();
        if(empty($obCategory)) {
            return $arResult;
        }
        
        //Add category ID
        $arResult['category_id'] = $obCategory->id;
        
        $sSorting = $this->getSorting();
        $arResult['sort'] = $sSorting;

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST, Category::CACHE_TAG_ELEMENT.'_'.$obCategory->id];
        $sCacheKey = $sSorting;

        $arProductIDList = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arProductIDList)) {
            
            //Get product ID list
            $arProductIDList = $this->getProductList($sSorting, $obCategory->id);
            if(empty($arProductIDList)) {
                return $arResult;
            }

            //Set cache data
            $iCacheTime = Settings::getCacheTime('cache_time_product_list');
            CCache::put($arCacheTags, $sCacheKey, $arProductIDList, $iCacheTime);
        }

        //Apply tag filter
        if(PluginManager::instance()->hasPlugin('Lovata.TagsShopaholic') && !empty($sTag)) {

            //Get cache data
            $arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST, \Lovata\TagsShopaholic\Models\Tag::CACHE_TAG_ELEMENT, Category::CACHE_TAG_ELEMENT.'_'.$obCategory->id];
            $sCacheKey = $sTag;

            $arProductListTag = CCache::get($arCacheTags, $sCacheKey);
            if(empty($arProductListTag)) {
                
                $arProductListTag = \Lovata\TagsShopaholic\Models\Tag::getProductList($sTag);

                //Set cache data
                $iCacheTime = Settings::getCacheTime('cache_time_product_list');
                CCache::put($arCacheTags, $sCacheKey, $arProductListTag, $iCacheTime);
            }
            
            if(!empty($arProductListTag)) {
                $arProductIDList = array_intersect($arProductIDList, $arProductListTag);
            }
        }
        
        //Apply filter
        if(PluginManager::instance()->hasPlugin('Lovata.FilterShopaholic')) {
            \Lovata\FilterShopaholic\Classes\CProductFilter::getList($arProductIDList, $obCategory);
        }

        //Apply pagination
        $arResult['count'] = count($arProductIDList);
        $arResult['max_page'] = ceil($arResult['count'] / $this->iProductOnPage);
        
        //Get last page number
        if($iPage == -1) {
            $iPage = $arResult['max_page'];
        }
        
        $arResult['page'] = $iPage;
        //TODO: Переделать пагинацию
        $arResult['pagination'] = Pagination::getPagination($iPage, $arResult['count'], $this->properties);
        
        //Get product ID list for page
        $arProductIDList = array_slice($arProductIDList, $this->iProductOnPage * ($iPage - 1), $this->iProductOnPage);
        
        //Get product data
        foreach($arProductIDList as $iProductID) {

            //Get product data
            $arProductData = Product::getCacheData($iProductID);
            if(!empty($arProductData)) {
                $arResult['list'][$iProductID] = $arProductData;
            }
        }

        $this->arResult[$sCategorySlug][$iPage] = $arResult;
        return $arResult;
    }

    /**
     * Get ajax product list
     * @return string
     */
    public function onAjaxRequest() {

        $iProductOnPage = $this->property('countPerPage');
        if($iProductOnPage > 0) {
            $this->iProductOnPage = $iProductOnPage;
        }
        
        return urldecode(Input::server('QUERY_STRING'));
    }

    /**
     * Get pagination data
     * @param string $sCategorySlug
     * @param int $iPage
     * @param string $sTag
     * @return array|mixed
     */
    public function getPagination($sCategorySlug, $iPage = 1, $sTag = null) {
        
        $arResult = $this->get($sCategorySlug, $iPage, $sTag);
        if(isset($arResult['pagination'])) {
            return $arResult['pagination'];
        }

        return [];
    }

    /**
     * Get count products
     * @param string $sCategorySlug
     * @param int $iPage
     * @param string $sTag
     * @return array|mixed
     */
    public function getCount($sCategorySlug, $iPage = 1, $sTag = null) {

        $arResult = $this->get($sCategorySlug, $iPage, $sTag);
        if(isset($arResult['count'])) {
            return $arResult['count'];
        }
        
        return 0;
    }
    
    /**
     * Get active sort
     * @param string $sCategorySlug
     * @param int $iPage
     * @param string $sTag
     * @return array|mixed
     */
    public function getActiveSort($sCategorySlug, $iPage = 1, $sTag = null) {

        $arResult = $this->get($sCategorySlug, $iPage, $sTag);
        if(isset($arResult['sort'])) {
            return $arResult['sort'];
        }

        return '';
    }

    /**
     * Get sorting
     * @return mixed|string
     */
    protected function getSorting() {
        
        $sSorting = Input::get('sort');
        if(empty($sSorting)) {
            $sSorting = $this->property('sorting');
        }
        
        if(!in_array($sSorting, [self::SORT_NO, self::SORT_PRICE_ASC, self::SORT_PRICE_DESC, self::SORT_NEW])) {
            $sSorting = $this->property('sorting');
        }
        
        return $sSorting;
    }
    
    /**
     * Get product ID list
     * @param string $sSorting
     * @param int $iCategoryID
     * @return array
     */
    protected function getProductList($sSorting, $iCategoryID) {

        $arProductIDList = [];
        if(empty($iCategoryID)) {
            return $arProductIDList;
        }
        
        switch($sSorting) {
            case self::SORT_PRICE_ASC :
                $arProductIDList = Offer::active()->hasActiveProduct()->getByCategory($iCategoryID)->groupBy('product_id')->orderBy('price', 'asc')->lists('product_id');
                break;
            case self::SORT_PRICE_DESC :
                $arProductIDList = Offer::active()->hasActiveProduct()->getByCategory($iCategoryID)->groupBy('product_id')->orderBy('price', 'desc')->lists('product_id');
                break;
            case self::SORT_POPULARITY_DESC :

                if(PluginManager::instance()->hasPlugin('Lovata.PopularityShopaholic')) {
                    $arProductIDList = Product::active()->getByCategory($iCategoryID)->orderBy('popularity', 'desc')->lists('id');
                } else {
                    $arProductIDList = Product::active()->getByCategory($iCategoryID)->lists('id');
                }
                
                break;
            default:
                $arProductIDList = Product::active()->getByCategory($iCategoryID)->orderBy('id', 'desc')->lists('id');
                break;
        }
        
        return $arProductIDList;
    }
}