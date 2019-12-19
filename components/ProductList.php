<?php namespace Lovata\Shopaholic\Components;

use Lang;
use System\Classes\PluginManager;

use Lovata\Toolbox\Classes\Component\SortingElementList;
use Lovata\Shopaholic\Classes\Collection\ProductCollection;
use Lovata\Shopaholic\Classes\Store\ProductListStore;

/**
 * Class ProductList
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * Compare for Shopaholic
 * @method array onAddToCompare()
 * @method array onRemoveFromCompare()
 * @method void onClearCompareList()
 *
 * Viewed products for Shopaholic
 * @method array onRemoveFromViewedProductList()
 * @method void onClearViewedProductList()
 *
 * Wish list for Shopaholic
 * @method array onAddToWishList()
 * @method array onRemoveFromWishList()
 * @method void onClearWishList()
 */
class ProductList extends SortingElementList
{
    /** @var array */
    protected $arPropertyList = [];

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'lovata.shopaholic::lang.component.product_list_name',
            'description' => 'lovata.shopaholic::lang.component.product_list_description',
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        $this->arPropertyList = [
            'sorting' => [
                'title'   => 'lovata.shopaholic::lang.component.product_list_sorting',
                'type'    => 'dropdown',
                'default' => ProductListStore::SORT_NO,
                'options' => [
                    ProductListStore::SORT_NO         => Lang::get('lovata.shopaholic::lang.component.sorting_no'),
                    ProductListStore::SORT_PRICE_ASC  => Lang::get('lovata.shopaholic::lang.component.sorting_price_asc'),
                    ProductListStore::SORT_PRICE_DESC => Lang::get('lovata.shopaholic::lang.component.sorting_price_desc'),
                    ProductListStore::SORT_NEW        => Lang::get('lovata.shopaholic::lang.component.sorting_new'),
                ],
            ],
        ];

        if (PluginManager::instance()->hasPlugin('Lovata.PopularityShopaholic')) {
            $this->arPropertyList['sorting']['options'][ProductListStore::SORT_POPULARITY_DESC] =
                Lang::get('lovata.shopaholic::lang.component.sorting_popularity_desc');
        }

        if (PluginManager::instance()->hasPlugin('Lovata.ReviewsShopaholic')) {
            $this->arPropertyList['sorting']['options'][ProductListStore::SORT_RATING_DESC] =
                Lang::get('lovata.shopaholic::lang.component.sorting_rating_desc');
            $this->arPropertyList['sorting']['options'][ProductListStore::SORT_RATING_ASC] =
                Lang::get('lovata.shopaholic::lang.component.sorting_rating_asc');
        }

        return $this->arPropertyList;
    }

    /**
     * Make element collection
     * @param array $arElementIDList
     *
     * @return ProductCollection
     */
    public function make($arElementIDList = null)
    {
        return ProductCollection::make($arElementIDList);
    }

    /**
     * Method for ajax request with empty response
     * @deprecated
     * @return bool
     */
    public function onAjaxRequest()
    {
        return true;
    }
}
