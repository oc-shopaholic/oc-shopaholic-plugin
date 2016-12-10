<?php namespace Lovata\Shopaholic\Components;

use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Models\Category;

/**
 * Class CategoryData
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CategoryData extends ComponentBase
{
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
     * Get category data with children
     * @param int $iCategoryID
     * @return array
     */
    public function get($iCategoryID)
    {
        return Category::getCacheData($iCategoryID);
    }
}