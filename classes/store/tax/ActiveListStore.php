<?php namespace Lovata\Shopaholic\Classes\Store\Tax;

use Lovata\Toolbox\Classes\Store\AbstractStoreWithoutParam;

use Lovata\Shopaholic\Models\Tax;

/**
 * Class ActiveListStore
 * @package Lovata\Shopaholic\Classes\Store\Tax
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
        $arElementIDList = (array) Tax::active()->lists('id');

        return $arElementIDList;
    }
}
