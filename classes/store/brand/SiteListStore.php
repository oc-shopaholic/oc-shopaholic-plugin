<?php namespace Lovata\Shopaholic\Classes\Store\Brand;

use Lovata\Toolbox\Classes\Store\AbstractStoreWithParam;

use Lovata\Shopaholic\Models\Brand;

/**
 * @package Lovata\Shopaholic\Classes\Store\Brand
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SiteListStore extends AbstractStoreWithParam
{
    protected static $instance;

    /**
     * Get ID list from database
     * @return array
     */
    protected function getIDListFromDB() : array
    {
        $arElementIDList = (array) Brand::getBySite($this->sValue)->pluck('id')->all();

        return $arElementIDList;
    }
}
