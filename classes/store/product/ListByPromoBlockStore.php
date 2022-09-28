<?php namespace Lovata\Shopaholic\Classes\Store\Product;

use DB;
use Lovata\Toolbox\Classes\Store\AbstractStoreWithParam;

/**
 * Class ListByPromoBlockStore
 * @package Lovata\Shopaholic\Classes\Store\Product
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ListByPromoBlockStore extends AbstractStoreWithParam
{
    protected static $instance;

    /**
     * Get ID list from database
     * @return array
     */
    protected function getIDListFromDB() : array
    {
        $arElementIDList = (array) DB::table('lovata_shopaholic_promo_block_relation')->where('promo_id', $this->sValue)->pluck('product_id')->all();

        return $arElementIDList;
    }
}
