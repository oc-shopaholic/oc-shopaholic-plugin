<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Toolbox\Classes\Collection\ElementCollection;

use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Store\OfferListStore;

/**
 * Class OfferCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * Filter for Shopaholic plugin
 * @method $this filterByPrice(float $fStartPrice, float $fStopPrice)
 * @method $this filterByDiscount()
 * @method $this filterByQuantity()
 * @method $this filterByProperty(array $arFilterList, \Lovata\FilterShopaholic\Classes\Collection\FilterPropertyCollection $obPropertyList)
 */
class OfferCollection extends ElementCollection
{
    const ITEM_CLASS = OfferItem::class;

    /**
     * Apply sorting
     * @param string $sSorting
     * @return $this
     */
    public function sort($sSorting)
    {
        $arResultIDList = OfferListStore::instance()->sorting->get($sSorting);

        return $this->applySorting($arResultIDList);
    }

    /**
     * Apply filter by active field
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\OfferCollectionTest::testActiveList()
     * @return $this
     */
    public function active()
    {
        $arResultIDList = OfferListStore::instance()->active->get();

        return $this->intersect($arResultIDList);
    }

    /**
     * Get the total count of all order positions
     * @return int
     */
    public function getTotalQuantity()
    {
        $iQuantityCount = 0;

        $arOfferList = $this->all();

        /** @var \Lovata\Shopaholic\Classes\Item\OfferItem $arOfferItem */
        foreach ($arOfferList as $arOfferItem) {
            $iQuantityCount += $arOfferItem->quantity;
        }

        return $iQuantityCount;
    }
}
