<?php namespace Lovata\Shopaholic\Components;

use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Classes\Collection\CategoryCollection;

/**
 * Class CategoryList
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CategoryList extends ComponentBase
{
    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'lovata.shopaholic::lang.component.category_list_name',
            'description' => 'lovata.shopaholic::lang.component.category_list_description',
        ];
    }

    /**
     * Make element collection
     * @param array $arElementIDList
     *
     * @return CategoryCollection
     */
    public function make($arElementIDList = null)
    {
        return CategoryCollection::make($arElementIDList);
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
