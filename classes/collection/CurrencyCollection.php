<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Toolbox\Classes\Collection\ElementCollection;

use Lovata\Shopaholic\Classes\Item\CurrencyItem;
use Lovata\Shopaholic\Classes\Store\CurrencyListStore;

/**
 * Class CurrencyCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CurrencyCollection extends ElementCollection
{
    const ITEM_CLASS = CurrencyItem::class;

    /**
     * Apply sorting
     * @return $this
     */
    public function sort()
    {
        //Get sorting list
        $arResultIDList = CurrencyListStore::instance()->sorting->get();

        return $this->applySorting($arResultIDList);
    }

    /**
     * Apply filter by active currency list
     * @return $this
     */
    public function active()
    {
        $arResultIDList = CurrencyListStore::instance()->active->get();

        return $this->intersect($arResultIDList);
    }
}
