<?php namespace Lovata\Shopaholic\Components;

use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Classes\Collection\BrandCollection;

/**
 * Class BrandList
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class BrandList extends ComponentBase
{
    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'          => 'lovata.shopaholic::lang.component.brand_list_name',
            'description'   => 'lovata.shopaholic::lang.component.brand_list_description',
        ];
    }

    /**
     * Make element collection
     * @param array $arElementIDList
     *
     * @return BrandCollection
     */
    public function make($arElementIDList = null)
    {
        return BrandCollection::make($arElementIDList);
    }

    /**
     * Method for ajax request with empty response
     * @deprecated
     * @return bool
     */
    public function onAjaxRequest()
    {
        return true;
    }
}
