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
        $obProductIDList = Product::active()->toBase()->pluck('id');
        if ($obProductIDList->isEmpty()) {
            return [];
        }

        //Check active offers
        if (Settings::get('check_offer_active')) {
            //Get product IDs for active offers
            $obProductIDListWithOffers = Offer::active()->toBase()
                ->pluck('product_id')
                ->unique();
            if ($obProductIDListWithOffers->isEmpty()) {
                return [];
            }

            $obProductIDList = $obProductIDList->intersect($obProductIDListWithOffers);
        }

        return $obProductIDList->toArray();
    }
}
