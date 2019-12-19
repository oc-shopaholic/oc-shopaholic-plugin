<?php namespace Lovata\Shopaholic\Components;

use Lang;
use Lovata\Toolbox\Classes\Component\SortingElementList;

use Lovata\Shopaholic\Classes\Store\PromoBlockListStore;
use Lovata\Shopaholic\Classes\Collection\PromoBlockCollection;

/**
 * Class PromoBlockList
 * @package Lovata\Shopaholic\Components
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class PromoBlockList extends SortingElementList
{
    /** @var array */
    protected $arPropertyList = [];

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'lovata.shopaholic::lang.component.promo_block_list_name',
            'description' => 'lovata.shopaholic::lang.component.promo_block_list_description',
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
                'default' => PromoBlockListStore::SORT_DEFAULT,
                'options' => [
                    PromoBlockListStore::SORT_DEFAULT         => Lang::get('lovata.shopaholic::lang.component.sorting_no'),
                    PromoBlockListStore::SORT_DATE_BEGIN_ASC  => Lang::get('lovata.shopaholic::lang.component.sorting_date_begin_asc'),
                    PromoBlockListStore::SORT_DATE_BEGIN_DESC => Lang::get('lovata.shopaholic::lang.component.sorting_date_begin_desc'),
                    PromoBlockListStore::SORT_DATE_END_ASC    => Lang::get('lovata.shopaholic::lang.component.sorting_date_end_asc'),
                    PromoBlockListStore::SORT_DATE_END_DESC   => Lang::get('lovata.shopaholic::lang.component.sorting_date_end_desc'),
                ],
            ],
        ];

        return $this->arPropertyList;
    }

    /**
     * Make element collection
     * @param array $arElementIDList
     *
     * @return PromoBlockCollection
     */
    public function make($arElementIDList = null)
    {
        return PromoBlockCollection::make($arElementIDList);
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
