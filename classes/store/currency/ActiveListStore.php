<?php namespace Lovata\Shopaholic\Classes\Store\Currency;

use Lovata\Toolbox\Classes\Store\AbstractStoreWithoutParam;

use Lovata\Shopaholic\Models\Currency;

/**
 * Class ActiveListStore
 * @package Lovata\Shopaholic\Classes\Store\Currency
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
        $arElementIDList = (array) Currency::active()->pluck('id')->all();

        return $arElementIDList;
    }
}
