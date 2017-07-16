<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Store\OfferListStore;
use Lovata\Toolbox\Classes\Collection\ElementCollection;
use Lovata\Toolbox\Traits\Collection\TraitCheckItemActive;
use Lovata\Toolbox\Traits\Collection\TraitCheckItemTrashed;

/**
 * Class OfferCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class OfferCollection extends ElementCollection
{
    use TraitCheckItemActive;
    use TraitCheckItemTrashed;

    /** @var OfferListStore */
    protected $obOfferListStore;

    /**
     * OfferCollection constructor.
     * @param OfferListStore $obOfferListStore
     */
    public function __construct(OfferListStore $obOfferListStore)
    {
        $this->obOfferListStore = $obOfferListStore;
    }

    /**
     * Make element item
     * @param int   $iElementID
     * @param \Lovata\Shopaholic\Models\Offer  $obElement
     *
     * @return OfferItem
     */
    protected function makeItem($iElementID, $obElement = null)
    {
        $obItem = OfferItem::make($iElementID, $obElement);
        $obItem->setCheckingActive($this->bCheckActive);
        $obItem->withTrashed($this->bCheckTrashed);

        return $obItem;
    }

    /**
     * Apply filter by active product list0
     * @return $this
     */
    public function active()
    {
        $arElementIDList = $this->obOfferListStore->getActiveList();
        return $this->intersect($arElementIDList);
    }
}