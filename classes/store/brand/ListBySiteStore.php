<?php namespace Lovata\Shopaholic\Classes\Store\Brand;

use Lovata\Toolbox\Classes\Store\AbstractStoreWithParam;

use Lovata\Shopaholic\Models\Brand;

/**
 * @package Lovata\Shopaholic\Classes\Store\Brand
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ListBySiteStore extends AbstractStoreWithParam
{
    protected static $instance;

    /**
     * Get ID list from database
     * @return array
     */
    protected function getIDListFromDB() : array
    {
        $arElementIDList = (array) Brand::whereHas('site', function($obQuery) {
            return $obQuery->where('id', $this->sValue);
        })
            ->orDoesntHave('site')
            ->pluck('id')
            ->all();

        return $arElementIDList;
    }
}
