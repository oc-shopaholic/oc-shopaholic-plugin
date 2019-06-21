<?php namespace Lovata\Shopaholic\Classes\Store\Offer;

use DB;
use Event;
use Lovata\Toolbox\Classes\Store\AbstractStoreWithParam;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Price;
use Lovata\Shopaholic\Classes\Helper\PriceTypeHelper;
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
        if ($this->sValue == OfferListStore::SORT_PRICE_ASC) {
            $arElementIDList = $this->getByPriceASC();
        } elseif ($this->sValue == OfferListStore::SORT_PRICE_DESC) {
            $arElementIDList = $this->getByPriceDESC();
        } elseif ($this->sValue == OfferListStore::SORT_NEW) {
            $arElementIDList = $this->getNewOfferList();
        } elseif ($this->sValue == OfferListStore::SORT_NO) {
            $arElementIDList = $this->getOfferList();
        } elseif (preg_match('%^'.OfferListStore::SORT_PRICE_ASC.'\|.+%', $this->sValue)) {
            $arElementIDList = $this->getByPriceTypeASC();
        } elseif (preg_match('%^'.OfferListStore::SORT_PRICE_DESC.'\|.+%', $this->sValue)) {
            $arElementIDList = $this->getByPriceTypeDESC();
        } else {
            $arElementIDList = $this->getCustomSortingList();
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
        $arElementIDList = (array) DB::table('lovata_shopaholic_prices')
            ->select('lovata_shopaholic_offers.id')
            ->whereNull('lovata_shopaholic_prices.price_type_id')
            ->where('lovata_shopaholic_offers.active', true)
            ->whereNull('lovata_shopaholic_offers.deleted_at')
            ->where('lovata_shopaholic_prices.item_type', Offer::class)
            ->orderBy('lovata_shopaholic_prices.price', 'asc')
            ->join('lovata_shopaholic_offers', 'lovata_shopaholic_offers.id', '=', 'lovata_shopaholic_prices.item_id')
            ->lists('id');

        return $arElementIDList;
    }

    /**
     * Get sorting ID list by offer price (DESC)
     * @return array
     */
    protected function getByPriceDESC() : array
    {
        $arElementIDList = (array) DB::table('lovata_shopaholic_prices')
            ->select('lovata_shopaholic_offers.id')
            ->whereNull('lovata_shopaholic_prices.price_type_id')
            ->where('lovata_shopaholic_offers.active', true)
            ->whereNull('lovata_shopaholic_offers.deleted_at')
            ->where('lovata_shopaholic_prices.item_type', Offer::class)
            ->orderBy('lovata_shopaholic_prices.price', 'desc')
            ->join('lovata_shopaholic_offers', 'lovata_shopaholic_offers.id', '=', 'lovata_shopaholic_prices.item_id')
            ->lists('id');

        return $arElementIDList;
    }

    /**
     * Get sorting ID list by offer price (ASC)
     * @return array
     */
    protected function getByPriceTypeASC() : array
    {
        $obPriceType = $this->getPriceTypeObject();
        if (empty($obPriceType)) {
            return $this->getByPriceASC();
        }

        $arElementIDList = (array) Price::getByItemType(Offer::class)
            ->getByPriceType($obPriceType->id)
            ->orderBy('price', 'asc')
            ->lists('item_id');

        return $arElementIDList;
    }

    /**
     * Get sorting ID list by offer price (DESC)
     * @return array
     */
    protected function getByPriceTypeDESC() : array
    {
        $obPriceType = $this->getPriceTypeObject();
        if (empty($obPriceType)) {
            return $this->getByPriceDESC();
        }

        $arElementIDList = (array) Price::getByItemType(Offer::class)
            ->getByPriceType($obPriceType->id)
            ->orderBy('price', 'desc')
            ->lists('item_id');

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

    /**
     * Get price type object
     * @return \Lovata\Shopaholic\Models\PriceType
     */
    protected function getPriceTypeObject()
    {
        $arValuePartList = explode('|', $this->sValue);
        $sPriceTypeCode = array_pop($arValuePartList);

        $obPriceType = PriceTypeHelper::instance()->findByCode($sPriceTypeCode);

        return $obPriceType;
    }
}
