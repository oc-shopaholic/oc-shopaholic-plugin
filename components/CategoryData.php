<?php namespace Lovata\Shopaholic\Components;

use Input;
use Request;
use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Models\Category;

/**
 * Class CategoryData
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CategoryData extends ComponentBase
{
    protected $iCategoryID = null;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'          => 'lovata.shopaholic::lang.component.category_data_name',
            'description'   => 'lovata.shopaholic::lang.component.category_data_description',
        ];
    }

    /**
     * Ajax listener
     * @return array|null
     */
    public function onGetProductData()
    {
        $this->iCategoryID = Input::get('category_id');
        return Category::getCacheData($this->iCategoryID);
    }

    /**
     * Ajax listener
     */
    public function onAjaxRequest()
    {
        $this->iCategoryID = Input::get('category_id');
    }

    /**
     * Get category data with children
     * @param int $iCategoryID
     * @return array|null
     */
    public function get($iCategoryID = null)
    {
        if(Request::ajax() && empty($iCategoryID)) {
            $arResult = Category::getCacheData($this->iCategoryID);
        } else {
            $arResult = Category::getCacheData($iCategoryID);
        }

        return $arResult;
    }
}