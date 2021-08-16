<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Toolbox\Classes\Collection\ElementCollection;

use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Classes\Store\BrandListStore;
use Lovata\Shopaholic\Classes\Item\CategoryItem;

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
     * @param int|array $arCategoryIDList
     * @param bool $bWithChildren
     * @return $this
     */
    public function category($arCategoryIDList, $bWithChildren = false)
    {
        if (!is_array($arCategoryIDList)) {
            $arCategoryIDList = [$arCategoryIDList];
        }

        $arResultIDList = [];
        foreach ($arCategoryIDList as $iCategoryID) {
            $arResultIDList = array_merge($arResultIDList, (array) BrandListStore::instance()->category->get($iCategoryID));
            if ($bWithChildren) {
                $arResultIDList = array_merge($arResultIDList, (array) $this->getIDListChildrenCategory($iCategoryID));
                $arResultIDList = array_unique($arResultIDList);
            }
        }

        return $this->intersect($arResultIDList);
    }

    /**
     * Get brand ID list for children categories
     * @param int $iCategoryID
     * @return array
     */
    protected function getIDListChildrenCategory($iCategoryID) : array
    {
        //Get category item
        $obCategoryItem = CategoryItem::make($iCategoryID);
        if ($obCategoryItem->isEmpty() || $obCategoryItem->children->isEmpty()) {
            return [];
        }

        $arResultIDList = [];
        foreach ($obCategoryItem->children as $obChildCategoryItem) {
            $arResultIDList = array_merge($arResultIDList, (array) BrandListStore::instance()->category->get($obChildCategoryItem->id));
            $arResultIDList = array_merge($arResultIDList, $this->getIDListChildrenCategory($obChildCategoryItem->id));
        }

        return $arResultIDList;
    }
}
