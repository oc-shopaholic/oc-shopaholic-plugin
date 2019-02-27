<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Toolbox\Classes\Collection\ElementCollection;

use Lovata\Shopaholic\Classes\Item\TaxItem;
use Lovata\Shopaholic\Classes\Store\TaxListStore;

/**
 * Class TaxCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class TaxCollection extends ElementCollection
{
    const ITEM_CLASS = TaxItem::class;

    /**
     * Apply sorting
     * @return $this
     */
    public function sort()
    {
        //Get sorting list
        $arResultIDList = TaxListStore::instance()->sorting->get();

        return $this->applySorting($arResultIDList);
    }

    /**
     * Apply filter by active tax list
     * @return $this
     */
    public function active()
    {
        $arResultIDList = TaxListStore::instance()->active->get();

        return $this->intersect($arResultIDList);
    }
}
