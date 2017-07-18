<?php namespace Lovata\Shopaholic\Components;

use Lovata\Shopaholic\Classes\Collection\CategoryCollection;
use Lovata\Toolbox\Classes\Component\ElementList;

/**
 * Class CategoryList
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CategoryList extends ElementList
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
     * Make element collection
     */
    protected function makeCollection()
    {
        $this->obItemCollection = CategoryCollection::make();
    }
}