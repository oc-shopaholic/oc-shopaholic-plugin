<?php namespace Lovata\Shopaholic\Classes\Collection;

use Site;
use Lovata\Toolbox\Classes\Collection\ElementCollection;

use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Store\CategoryListStore;

/**
 * Class CategoryCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * Search for Shopaholic, Sphinx for Shopaholic
 * @method $this search(string $sSearch)
 */
class CategoryCollection extends ElementCollection
{
    const ITEM_CLASS = CategoryItem::class;

    /**
     * Set to element ID list top level category ID list
     * @return CategoryCollection
     */
    public function tree()
    {
        $arResultIDList = CategoryListStore::instance()->top_level->get();

        return $this->applySorting($arResultIDList);
    }

    /**
     * Apply filter by active field
     * @return $this
     */
    public function active()
    {
        $arResultIDList = CategoryListStore::instance()->active->get();

        return $this->intersect($arResultIDList);
    }

    /**
     * Apply filter by site_list field
     * @return $this
     */
    public function site($iSiteID = null): self
    {
        $iSiteID = empty($iSiteID) ? Site::getSiteIdFromContext() : $iSiteID;
        $arResultIDList = CategoryListStore::instance()->site->get($iSiteID);

        return $this->intersect($arResultIDList);
    }
}
