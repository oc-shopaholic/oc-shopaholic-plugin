<?php namespace Lovata\Shopaholic\Classes\Store\Currency;

use Lovata\Toolbox\Classes\Store\AbstractStoreWithoutParam;

use Lovata\Shopaholic\Models\Currency;

/**
 * Class SortingListStore
 * @package Lovata\Shopaholic\Classes\Store\Currency
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SortingListStore extends AbstractStoreWithoutParam
{
    protected static $instance;

    /**
     * Get ID list from database
     * @return array
     */
    protected function getIDListFromDB() : array
    {
        $arElementIDList = (array) Currency::orderBy('sort_order', 'asc')->pluck('id')->all();

        return $arElementIDList;
    }
}
