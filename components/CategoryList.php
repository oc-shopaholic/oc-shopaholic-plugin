<?php namespace Lovata\Shopaholic\Components;

use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Classes\Collection\CategoryCollection;
use Lovata\Shopaholic\Classes\Item\CategoryItem;

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
            'name'          => 'lovata.shopaholic::lang.component.category_list_name',
            'description'   => 'lovata.shopaholic::lang.component.category_list_description',
        ];
    }

    /**
     * Get category tree
     * @return array|CategoryItem[]|null
     */
    public function get()
    {
        $obCategoryCollection = CategoryCollection::make();
        return $obCategoryCollection->tree()->getList();
    }
}