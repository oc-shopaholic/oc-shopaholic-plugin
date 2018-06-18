<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Toolbox\Classes\Collection\ElementCollection;

use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Store\CategoryListStore;

/**
 * Class CategoryCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @see \Lovata\Shopaholic\Tests\Unit\Collection\CategoryCollectionTest
 * @link https://github.com/lovata/oc-shopaholic-plugin/wiki/CategoryCollection
 *
 * Search for Shopaholic, Sphinx for Shopaholic
 * @method $this search(string $sSearch)
 */
class CategoryCollection extends ElementCollection
{
    const ITEM_CLASS = CategoryItem::class;

    /**
     * Set to element ID list top level category ID list
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\CategoryCollectionTest::testTreeMethod()
     * @return CategoryCollection
     */
    public function tree()
    {
        $arResultIDList = CategoryListStore::instance()->top_level->get();

        return $this->applySorting($arResultIDList);
    }

    /**
     * Apply filter by active field
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\CategoryCollectionTest::testActiveList()
     * @return $this
     */
    public function active()
    {
        $arResultIDList = CategoryListStore::instance()->active->get();

        return $this->intersect($arResultIDList);
    }
}
