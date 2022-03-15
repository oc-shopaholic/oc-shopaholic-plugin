<?php namespace Lovata\Shopaholic\Classes\Store\Product;

use Lovata\Toolbox\Classes\Store\AbstractStoreWithoutParam;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\Settings;

/**
 * Class ActiveListStore
 * @package Lovata\Shopaholic\Classes\Store\Product
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ActiveListStore extends AbstractStoreWithoutParam
{
    protected static $instance;

    /**
     * Get ID list from database
     * @return array
     */
    protected function getIDListFromDB() : array
    {
        //Get product ID list
        $arProductIDList = (array) Product::active()->pluck('id')->all();
        if (empty($arProductIDList)) {
            return [];
        }

        //Check active offers
        if (Settings::getValue('check_offer_active')) {
            //Get product list with active offers
            $arProductIDListWithOffers = (array) Offer::active()->groupBy('product_id')->pluck('product_id')->all();
            if (empty($arProductIDListWithOffers)) {
                return [];
            }

            $arProductIDList = array_intersect($arProductIDList, $arProductIDListWithOffers);
        }

        return $arProductIDList;
    }
}
