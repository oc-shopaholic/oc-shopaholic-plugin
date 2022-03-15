<?php namespace Lovata\Shopaholic\Classes\Store\Category;

use Lovata\Toolbox\Classes\Store\AbstractStoreWithoutParam;

use Lovata\Shopaholic\Models\Category;

/**
 * Class TopLevelListStore
 * @package Lovata\Shopaholic\Classes\Store\Category
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class TopLevelListStore extends AbstractStoreWithoutParam
{
    protected static $instance;

    /**
     * Get ID list from database
     * @return array
     */
    protected function getIDListFromDB() : array
    {
        $arElementIDList = (array) Category::active()
            ->where(function($obQuery) {
                /** @var Category $obQuery */
                $obQuery->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->orderBy('nest_left', 'asc')
            ->pluck('id')->all();

        return $arElementIDList;
    }
}
