<?php namespace Lovata\Shopaholic\Components;

use Lang;
use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Models\Category;

/**
 * Class CategoryData
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CategoryData extends ComponentBase
{
    
    public function componentDetails()
    {
        return [
            'name' => Lang::get('lovata.shopaholic::lang.component.category_data_name'),
            'description' => Lang::get('lovata.shopaholic::lang.component.get_category_data_description'),
        ];
    }

    /**
     * Get category data with children
     * @param int $iCategoryID
     * @return array
     */
    public function get($iCategoryID) {
        return Category::getCacheData($iCategoryID);
    }
}