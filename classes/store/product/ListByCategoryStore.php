<?php namespace Lovata\Shopaholic\Classes\Store\Product;

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
     * Get ID list from database
     * @return array
     */
    protected function getIDListFromDB() : array
    {
        $arElementIDList = (array) Product::getByCategory($this->sValue)->lists('id');

        return $arElementIDList;
    }
}
