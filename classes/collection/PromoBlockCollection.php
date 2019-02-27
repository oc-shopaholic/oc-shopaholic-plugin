<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Toolbox\Classes\Collection\ElementCollection;

use Lovata\Shopaholic\Classes\Item\PromoBlockItem;
use Lovata\Shopaholic\Classes\Store\PromoBlockListStore;

/**
 * Class PromoBlockCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class PromoBlockCollection extends ElementCollection
{
    const ITEM_CLASS = PromoBlockItem::class;

    /**
     * Apply sorting
     * @param string $sSorting
     * @return $this
     */
    public function sort($sSorting)
    {
        //Get sorting list
        $arResultIDList = PromoBlockListStore::instance()->sorting->get($sSorting);

        return $this->applySorting($arResultIDList);
    }

    /**
     * Apply filter by active field
     * @return $this
     */
    public function active()
    {
        $arResultIDList = PromoBlockListStore::instance()->active->get();

        return $this->intersect($arResultIDList);
    }

    /**
     * Apply filter by hidden field
     * @return $this
     */
    public function hidden()
    {
        $arResultIDList = PromoBlockListStore::instance()->hidden->get();

        return $this->intersect($arResultIDList);
    }

    /**
     * Apply filter by hidden field
     * @return $this
     */
    public function notHidden()
    {
        $arResultIDList = PromoBlockListStore::instance()->not_hidden->get();

        return $this->intersect($arResultIDList);
    }
}
