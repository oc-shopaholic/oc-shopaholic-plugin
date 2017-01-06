<?php namespace Lovata\Shopaholic\Components;

use Input;
use Lang;
use Cms\Classes\ComponentBase;
use Kharanenka\Helper\Pagination;
use Lovata\Shopaholic\Classes\ProductListStore;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Product;
use System\Classes\PluginManager;
use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Plugin;
use Lovata\Toolbox\Plugin as ToolboxPlugin;

/**
 * Class ProductList
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductList extends ComponentBase
{
    protected $iProductOnPage = 10;
    protected $sSorting;

    protected static $arResult = [];
    protected static $arProductIDList = [];

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'          => 'lovata.shopaholic::lang.component.product_list_name',
            'description'   => 'lovata.shopaholic::lang.component.product_list_description',
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        $arProperties = [
            'sorting' => [
                'title'     => 'lovata.shopaholic::lang.component.product_list_sorting',
                'type'      => 'dropdown',
                'default'   => ProductListStore::SORT_NO,
                'options'   => [
                    ProductListStore::SORT_NO           => Lang::get('lovata.shopaholic::lang.component.sorting_no'),
                    ProductListStore::SORT_PRICE_ASC    => Lang::get('lovata.shopaholic::lang.component.sorting_price_asc'),
                    ProductListStore::SORT_PRICE_DESC   => Lang::get('lovata.shopaholic::lang.component.sorting_price_desc'),
                    ProductListStore::SORT_NEW          => Lang::get('lovata.shopaholic::lang.component.sorting_new'),
                ],

            ],
        ];

        if(PluginManager::instance()->hasPlugin('Lovata.PopularityShopaholic')) {
            $arProperties['sorting']['options'][ProductListStore::SORT_POPULARITY_DESC] = Lang::get('lovata.shopaholic::lang.component.sorting_popularity_desc');
        }
        
        $arProperties = array_merge($arProperties, Pagination::getProperties(ToolboxPlugin::NAME));
        return $arProperties;
    }

    public function onRun()
    {
        $this->initData();
        if(!\Request::ajax()) {
            $this->addJs('/plugins/lovata/shopaholic/assets/js/main.js');
        }
    }

    /**
     * Init start component data
     */
    protected function initData()
    {
        $iProductOnPage = $this->property('count_per_page');
        if($iProductOnPage > 0) {
            $this->iProductOnPage = $iProductOnPage;
        }
        
        $this->setActiveSorting();
    }

    /**
     * Get product list by page number
     * @param int $iCategoryID
     * @param int $iPage
     * @param string $iTagID
     * @param bool $bPageFromRequest
     * @return array
     */
    public function getData($iPage = 1, $iCategoryID = 0, $iTagID = null, $bPageFromRequest = true)
    {
        if(empty($iCategoryID)) {
            $iCategoryID = 0;
        }

        $arResult = [
            'list' => [],
            'pagination' => [],
            'page' => $iPage,
            'count' => 0,
            'category_id' => $iCategoryID,
        ];

        //Get page from request
        $iRequestPage = Input::get('page');
        if(!empty($iRequestPage) && $bPageFromRequest) {
            $iPage = $iRequestPage;
        }

        //Check page value
        if($iPage < 1) {
            $iPage = 1;
        }

        if(isset(self::$arResult[$iCategoryID]) && isset(self::$arResult[$iCategoryID][$iPage])) {
            return self::$arResult[$iCategoryID][$iPage];
        }

        //Get product ID list
        $arProductIDList = $this->getProductIDList($iCategoryID, $iTagID);
        if(empty($arProductIDList)) {
            return $arResult;
        }

        //Apply pagination
        $arResult['count'] = count($arProductIDList);
        $arResult['max_page'] = ceil($arResult['count'] / $this->iProductOnPage);
        
        $arResult['page'] = $iPage;
        $arResult['pagination'] = Pagination::get($iPage, $arResult['count'], $this->properties);
        
        //Get product ID list for page
        $arProductIDList = array_slice($arProductIDList, $this->iProductOnPage * ($iPage - 1), $this->iProductOnPage);
        
        //Get product data
        foreach($arProductIDList as $iProductID) {

            //Get product data
            $arProductData = Product::getCacheData($iProductID);
            if(empty($arProductData)) {
                continue;
            }

            $arResult['list'][$iProductID] = $arProductData;
        }

        self::$arResult[$iCategoryID][$iPage] = $arResult;
        return $arResult;
    }

    /**
     * Get product ID list
     * @param int $iCategoryID
     * @param int $iTagID
     * @return array
     */
    public function getProductIDList($iCategoryID, $iTagID)
    {
        if(isset(self::$arProductIDList[$iCategoryID])) {
            return self::$arProductIDList[$iCategoryID];
        }

        //Get cached product list by category filter and sorting by $this->sSorting
        $arProductIDList = ProductListStore::getSortingByCategory($this->sSorting, $iCategoryID);
        if(empty($arProductIDList)) {
            return [];
        }

        //Apply tag filter
        //TODO: Перенести в плагин тегов и обратить внимание на категорию 0
        if(PluginManager::instance()->hasPlugin('Lovata.TagsShopaholic') && !empty($iTagID)) {

            //Get cache data
            $arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST, \Lovata\TagsShopaholic\Models\Tag::CACHE_TAG_ELEMENT, Category::CACHE_TAG_ELEMENT.'_'.$iCategoryID];
            $sCacheKey = $iTagID;

            $arProductListTag = CCache::get($arCacheTags, $sCacheKey);
            if(empty($arProductListTag)) {

                $arProductListTag = \Lovata\TagsShopaholic\Models\Tag::getProductList($iTagID);

                //Set cache data
                CCache::forever($arCacheTags, $sCacheKey, $arProductListTag);
            }

            if(!empty($arProductListTag)) {
                $arProductIDList = array_intersect($arProductIDList, $arProductListTag);
            }
        }

        //Apply custom filter
        if(PluginManager::instance()->hasPlugin('Lovata.CustomShopaholic')) {
            \Lovata\CustomShopaholic\Classes\ProductListExtend::applyCustomFilter($arProductIDList, $iCategoryID);
            if(empty($arProductIDList)) {
                return [];
            }
        }

        //Apply filter
        if(PluginManager::instance()->hasPlugin('Lovata.FilterShopaholic')) {
            \Lovata\FilterShopaholic\Classes\CProductFilter::getList($arProductIDList, $iCategoryID);
            if(empty($arProductIDList)) {
                return [];
            }
        }

        self::$arProductIDList[$iCategoryID] = $arProductIDList;
        return $arProductIDList;
    }
    
    /**
     * Get ajax product list
     * @return string
     */
    public function onAjaxRequest()
    {
        $this->initData();

        //Get response type from request
        $sResponseType = Input::get('response_type');

        $iCategoryID = Input::get('category_id');
        $iTagID = Input::get('tag_id');

        //Get 'QUERY_STRING'
        $sQueryString = urldecode(Input::server('QUERY_STRING'));

        switch($sResponseType) {
            case 'full':

                $arResult = $this->getData(1, $iCategoryID, $iTagID);
                $arResult['query_string'] = $sQueryString;
                return $arResult;
            case 'id_list':

                //Get product ID list
                $arResult = ['list' => null];
                $arProductIDList = $this->getProductIDList($iCategoryID, $iTagID);
                if(!empty($arProductIDList)) {
                    $arResult = ['list' => array_values($arProductIDList)];
                }

                $arResult['query_string'] = $sQueryString;
                return $arResult;
            default:
                return $sQueryString;
        }
    }

    /**
     * Get product list
     * @param int $iCategoryID
     * @param int $iPage
     * @param string $sTag
     * @param bool $bPageFromRequest
     * @return array|mixed
     */
    public function get($iPage = 1, $iCategoryID = 0, $sTag = null, $bPageFromRequest = true)
    {
        $arResult = $this->getData($iPage, $iCategoryID, $sTag, $bPageFromRequest);
        if(isset($arResult['list'])) {
            return $arResult['list'];
        }

        return [];
    }

    /**
     * Get pagination data
     * @param int $iCategoryID
     * @param int $iPage
     * @param string $sTag
     * @param bool $bPageFromRequest
     * @return array|mixed
     */
    public function getPagination($iPage = 1, $iCategoryID = 0, $sTag = null, $bPageFromRequest = true)
    {
        $arResult = $this->getData($iPage, $iCategoryID, $sTag, $bPageFromRequest);
        if(isset($arResult['pagination'])) {
            return $arResult['pagination'];
        }

        return [];
    }

    /**
     * Get count products
     * @param int $iCategoryID
     * @param int $iPage
     * @param string $sTag
     * @param bool $bPageFromRequest
     * @return array|mixed
     */
    public function getCount($iPage = 1, $iCategoryID = 0, $sTag = null, $bPageFromRequest = true)
    {
        $arResult = $this->getData($iPage, $iCategoryID, $sTag, $bPageFromRequest);
        if(isset($arResult['count'])) {
            return $arResult['count'];
        }
        
        return 0;
    }

    /**
     * Get active sorting
     * @return string
     */
    public function getSorting()
    {
        return $this->sSorting;
    }

    /**
     * Set active sorting
     */
    protected function setActiveSorting()
    {
        $this->sSorting = Input::get('sort');
        if(empty($this->sSorting)) {
            $this->sSorting = $this->property('sorting');
        }
        
        if(!in_array($this->sSorting, ProductListStore::getAvailableSorting()) && !preg_match('%^'.ProductListStore::SORT_CUSTOM.'%', $this->sSorting)) {
            $this->sSorting = $this->property('sorting');
        }
    }
}