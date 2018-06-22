<?php namespace Lovata\Shopaholic\Classes\Store\Offer;

use Event;
use Lovata\Toolbox\Classes\Store\AbstractStoreWithParam;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Classes\Store\OfferListStore;

/**
 * Class SortingListStore
 * @package Lovata\Shopaholic\Classes\Store\Offer
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SortingListStore extends AbstractStoreWithParam
{
    protected static $instance;

    /**
     * Get ID list from database
     * @return array
     */
    protected function getIDListFromDB() : array
    {
        switch ($this->sValue) {
            case OfferListStore::SORT_PRICE_ASC:
                $arElementIDList = $this->getByPriceASC();
                break;
            case OfferListStore::SORT_PRICE_DESC:
                $arElementIDList = $this->getByPriceDESC();
                break;
            case OfferListStore::SORT_NEW:
                $arElementIDList = $this->getNewOfferList();
                break;
            case OfferListStore::SORT_NO:
                $arElementIDList = $this->getOfferList();
                break;
            default:
                $arElementIDList = $this->getCustomSortingList();
                break;
        }

        return $arElementIDList;
    }

    /**
     * Get product list with custom sorting
     * @return array
     */
    protected function getCustomSortingList() : array
    {
        $arEventResult = Event::fire('shopaholic.sorting.offer.get.list', [$this->sValue]);
        if (empty($arEventResult)) {
            return [];
        }

        $arElementIDList = [];
        foreach ($arEventResult as $arEventOfferIDList) {
            if (empty($arEventOfferIDList) || !is_array($arEventOfferIDList)) {
                continue;
            }

            $arElementIDList = $arEventOfferIDList;
            break;
        }

        return $arElementIDList;
    }

    /**
     * Get sorting ID list by offer price (ASC)
     * @return array
     */
    protected function getByPriceASC() : array
    {
        //Get product ID list (sort by offer price)
        //We can not use groupBy() in this place
        $arElementIDList = (array) Offer::orderBy('price', 'asc')->lists('id');
        $arElementIDList = array_unique($arElementIDList);

        return $arElementIDList;
    }

    /**
     * Get sorting ID list by offer price (DESC)
     * @return array
     */
    protected function getByPriceDESC() : array
    {
        //Get product ID list (sort by offer price)
        //We can not use groupBy() in this place
        $arElementIDList = (array) Offer::orderBy('price', 'desc')->lists('id');
        $arElementIDList = array_unique($arElementIDList);

        return $arElementIDList;
    }

    /**
     * Get new products
     * @return array
     */
    protected function getNewOfferList() : array
    {
        $arElementIDList = (array) Offer::orderBy('id', 'desc')->lists('id');

        return $arElementIDList;
    }

    /**
     * Get new products
     * @return array
     */
    protected function getOfferList() : array
    {
        $arElementIDList = (array) Offer::lists('id');

        return $arElementIDList;
    }
}
