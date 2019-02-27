<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Toolbox\Classes\Collection\ElementCollection;

use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Classes\Store\BrandListStore;

/**
 * Class BrandCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * Search for Shopaholic, Sphinx for Shopaholic
 * @method $this search(string $sSearch)
 */
class BrandCollection extends ElementCollection
{
    const ITEM_CLASS = BrandItem::class;

    /**
     * Apply sorting
     * @return $this
     */
    public function sort()
    {
        //Get sorting list
        $arResultIDList = BrandListStore::instance()->sorting->get();

        return $this->applySorting($arResultIDList);
    }

    /**
     * Apply filter by active brand list
     * @return $this
     */
    public function active()
    {
        $arResultIDList = BrandListStore::instance()->active->get();

        return $this->intersect($arResultIDList);
    }

    /**
     * Filter brand list by category ID
     * @param int $iCategoryID
     * @return $this
     */
    public function category($iCategoryID)
    {
        $arResultIDList = BrandListStore::instance()->category->get($iCategoryID);

        return $this->intersect($arResultIDList);
    }
}
