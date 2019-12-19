<?php namespace Lovata\Shopaholic\Components;

use Lovata\Toolbox\Classes\Component\ElementData;

use Lovata\Shopaholic\Classes\Item\CategoryItem;

/**
 * Class CategoryData
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CategoryData extends ElementData
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
     * Make new element item
     * @param int $iElementID
     * @return CategoryItem
     */
    protected function makeItem($iElementID)
    {
        return CategoryItem::make($iElementID);
    }
}
