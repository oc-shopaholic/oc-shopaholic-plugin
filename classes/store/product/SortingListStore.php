<?php namespace Lovata\Shopaholic\Classes\Store\Product;

use DB;
use Event;
use Lovata\Shopaholic\Classes\Helper\PriceTypeHelper;
use Lovata\Toolbox\Classes\Store\AbstractStoreWithParam;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Store\ProductListStore;

/**
 * Class SortingListStore
 * @package Lovata\Shopaholic\Classes\Store\Product
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
        if ($this->sValue == ProductListStore::SORT_PRICE_ASC) {
            $arElementIDList = $this->getByPriceASC();
        } elseif ($this->sValue == ProductListStore::SORT_PRICE_DESC) {
            $arElementIDList = $this->getByPriceDESC();
        } elseif ($this->sValue == ProductListStore::SORT_NEW) {
            $arElementIDList = $this->getNewProductList();
        } elseif ($this->sValue == ProductListStore::SORT_NO) {
            $arElementIDList = $this->getProductList();
        } elseif (preg_match('%^'.ProductListStore::SORT_PRICE_ASC.'\|.+%', $this->sValue)) {
            $arElementIDList = $this->getByPriceTypeASC();
        } elseif (preg_match('%^'.ProductListStore::SORT_PRICE_DESC.'\|.+%', $this->sValue)) {
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
        $arEventResult = Event::fire('shopaholic.sorting.get.list', [$this->sValue]);
        if (empty($arEventResult)) {
            return [];
        }

        $arElementIDList = [];
        foreach ($arEventResult as $arEventProductIDList) {
            if (empty($arEventProductIDList) || !is_array($arEventProductIDList)) {
                continue;
            }

            $arElementIDList = $arEventProductIDList;
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
        $arElementIDList = (array) DB::table('lovata_shopaholic_prices')
            ->select('lovata_shopaholic_offers.product_id')
            ->whereNull('lovata_shopaholic_prices.price_type_id')
            ->where('lovata_shopaholic_offers.active', true)
            ->whereNull('lovata_shopaholic_offers.deleted_at')
            ->where('lovata_shopaholic_prices.item_type', Offer::class)
            ->orderBy('lovata_shopaholic_prices.price', 'asc')
            ->join('lovata_shopaholic_offers', 'lovata_shopaholic_offers.id', '=', 'lovata_shopaholic_prices.item_id')
            ->pluck('product_id')->all();
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
        $arElementIDList = (array) DB::table('lovata_shopaholic_prices')
            ->select('lovata_shopaholic_offers.product_id')
            ->whereNull('lovata_shopaholic_prices.price_type_id')
            ->where('lovata_shopaholic_offers.active', true)
            ->whereNull('lovata_shopaholic_offers.deleted_at')
            ->where('lovata_shopaholic_prices.item_type', Offer::class)
            ->orderBy('lovata_shopaholic_prices.price', 'desc')
            ->join('lovata_shopaholic_offers', 'lovata_shopaholic_offers.id', '=', 'lovata_shopaholic_prices.item_id')
            ->pluck('product_id')->all();
        $arElementIDList = array_unique($arElementIDList);

        return $arElementIDList;
    }

    /**
     * Get sorting ID list by offer price with filter by price type (ASC)
     * @return array
     */
    protected function getByPriceTypeASC() : array
    {
        $obPriceType = $this->getPriceTypeObject();
        if (empty($obPriceType)) {
            return $this->getByPriceASC();
        }

        //Get product ID list (sort by offer price)
        //We can not use groupBy() in this place
        $arElementIDList = (array) DB::table('lovata_shopaholic_prices')
            ->select('lovata_shopaholic_offers.product_id')
            ->where('lovata_shopaholic_prices.price_type_id', $obPriceType->id)
            ->where('lovata_shopaholic_offers.active', true)
            ->where('lovata_shopaholic_prices.item_type', Offer::class)
            ->orderBy('lovata_shopaholic_prices.price', 'asc')
            ->join('lovata_shopaholic_offers', 'lovata_shopaholic_offers.id', '=', 'lovata_shopaholic_prices.item_id')
            ->pluck('product_id')->all();
        $arElementIDList = array_unique($arElementIDList);

        return $arElementIDList;
    }

    /**
     * Get sorting ID list by offer price with filter by price type (DESC)
     * @return array
     */
    protected function getByPriceTypeDESC() : array
    {
        $obPriceType = $this->getPriceTypeObject();
        if (empty($obPriceType)) {
            return $this->getByPriceDESC();
        }

        //Get product ID list (sort by offer price)
        //We can not use groupBy() in this place
        $arElementIDList = (array) DB::table('lovata_shopaholic_prices')
            ->select('lovata_shopaholic_offers.product_id')
            ->where('lovata_shopaholic_prices.price_type_id', $obPriceType->id)
            ->where('lovata_shopaholic_offers.active', true)
            ->where('lovata_shopaholic_prices.item_type', Offer::class)
            ->orderBy('lovata_shopaholic_prices.price', 'desc')
            ->join('lovata_shopaholic_offers', 'lovata_shopaholic_offers.id', '=', 'lovata_shopaholic_prices.item_id')
            ->pluck('product_id')->all();
        $arElementIDList = array_unique($arElementIDList);

        return $arElementIDList;
    }

    /**
     * Get new products
     * @return array
     */
    protected function getNewProductList() : array
    {
        $arElementIDList = (array) Product::orderBy('id', 'desc')->pluck('id')->all();

        return $arElementIDList;
    }

    /**
     * Get new products
     * @return array
     */
    protected function getProductList() : array
    {
        $arElementIDList = (array) Product::pluck('id')->all();

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
