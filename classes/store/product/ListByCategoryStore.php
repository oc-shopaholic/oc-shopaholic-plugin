<?php namespace Lovata\Shopaholic\Classes\Store\Product;

use DB;
use Event;
use Lovata\Toolbox\Classes\Store\AbstractStoreWithParam;

use Lovata\Shopaholic\Models\Product;

/**
 * Class ListByCategoryStore
 * @package Lovata\Shopaholic\Classes\Store\Product
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ListByCategoryStore extends AbstractStoreWithParam
{
    protected static $instance;

    /**
     * Clear element ID list
     * @param string $sFilterValue
     */
    public function clear($sFilterValue)
    {
        parent::clear($sFilterValue);

        Event::fire('shopaholic.product.category.clear', [$sFilterValue]);
    }

    /**
     * Get ID list from database
     * @return array
     */
    protected function getIDListFromDB() : array
    {
        $arElementIDList = (array) Product::getByCategory($this->sValue)->lists('id');

        //Get product ID list for additional category relation
        $arAdditionalElementIDList = (array) DB::table('lovata_shopaholic_additional_categories')->where('category_id', $this->sValue)->lists('product_id');
        $arElementIDList = array_merge($arElementIDList, $arAdditionalElementIDList);
        $arElementIDList = array_unique($arElementIDList);

        return $arElementIDList;
    }
}
