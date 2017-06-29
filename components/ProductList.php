<?php namespace Lovata\Shopaholic\Components;

use App;
use Input;
use Lang;
use Cms\Classes\ComponentBase;
use System\Classes\PluginManager;

use Kharanenka\Helper\Pagination;
use Lovata\Toolbox\Plugin as ToolboxPlugin;
use Lovata\Shopaholic\Classes\Store\ProductListStore;
use Lovata\Shopaholic\Classes\Collection\ProductCollection;

/**
 * Class ProductList
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductList extends ComponentBase
{
    protected $iProductOnPage = 10;
    protected $sSorting;

    /** @var  ProductListStore */
    protected $obProductListStore;

    protected static $arResult = [];

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

    /**
     * Init start component data
     */
    public function init()
    {
        $this->obProductListStore = App::make(ProductListStore::class);

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
     * @return array
     */
    public function getData($iCategoryID, $iPage = 1)
    {
        $arResult = [
            'list'        => [],
            'pagination'  => [],
            'page'        => 1,
            'max_page'    => 1,
            'count'       => 0,
            'category_id' => $iCategoryID,
        ];

        if(empty($iCategoryID)) {
            return $arResult;
        }

        //Get page from request
        $iRequestPage = Input::get('page');
        if(!empty($iRequestPage)) {
            $iPage = $iRequestPage;
        }

        //Check page value
        if($iPage < 1) {
            $iPage = 1;
        }

        if(isset(self::$arResult[$iCategoryID]) && isset(self::$arResult[$iCategoryID][$iPage])) {
            return self::$arResult[$iCategoryID][$iPage];
        }

        //Get product collection
        $obProductCollection = ProductCollection::make()
            ->sortBy($this->sSorting)
            ->active()
            ->category($iCategoryID);

        //Apply pagination
        $arResult['count'] = $obProductCollection->count();
        $arResult['max_page'] = ceil($arResult['count'] / $this->iProductOnPage);
        
        $arResult['page'] = $iPage;
        $arResult['pagination'] = Pagination::get($iPage, $arResult['count'], $this->properties);

        //Apply pagination
        $obProductCollection->pagination($iPage, $this->iProductOnPage);

        //Get product item list
        $arResult['list'] = $obProductCollection->getList();

        self::$arResult[$iCategoryID][$iPage] = $arResult;
        return $arResult;
    }
    
    /**
     * Process ajax request
     * @return mixed
     */
    public function onAjaxRequest()
    {
        return true;
    }

    /**
     * Get product list
     * @param int $iCategoryID
     * @param int $iPage
     * @return array
     */
    public function get($iCategoryID, $iPage = 1)
    {
        $arResult = $this->getData($iCategoryID, $iPage);
        if(isset($arResult['list'])) {
            return $arResult['list'];
        }

        return [];
    }

    /**
     * Get pagination data
     * @param int $iCategoryID
     * @param int $iPage
     * @return array
     */
    public function getPagination($iCategoryID, $iPage = 1)
    {
        $arResult = $this->getData($iCategoryID, $iPage);
        if(isset($arResult['pagination'])) {
            return $arResult['pagination'];
        }

        return [];
    }

    /**
     * Get product count
     * @param int $iCategoryID
     * @return array|mixed
     */
    public function getCount($iCategoryID)
    {
        $arResult = $this->getData($iCategoryID);
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
        
        if(!in_array($this->sSorting, $this->obProductListStore->getAvailableSorting())) {
            $this->sSorting = $this->property('sorting');
        }
    }
}