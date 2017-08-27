<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Toolbox\Classes\Collection\ElementCollection;

use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Store\OfferListStore;

/**
 * Class OfferCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class OfferCollection extends ElementCollection
{
    /** @var OfferListStore */
    protected $obOfferListStore;

    /**
     * OfferCollection constructor.
     * @param OfferListStore $obOfferListStore
     */
    public function __construct(OfferListStore $obOfferListStore)
    {
        $this->obOfferListStore = $obOfferListStore;
        parent::__construct();
    }

    /**
     * Make element item
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\OfferCollectionTest::testCollectionItem()
     * @param int                             $iElementID
     * @param \Lovata\Shopaholic\Models\Offer $obElement
     *
     * @return OfferItem
     */
    protected function makeItem($iElementID, $obElement = null)
    {
        return OfferItem::make($iElementID, $obElement);
    }

    /**
     * Sort list by
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\OfferCollectionTest::testSortingByPrice()
     * @param string $sSorting
     * @return $this
     */
    public function sort($sSorting)
    {
        if(!$this->isClear() && $this->isEmpty()) {
            return $this;
        }

        //Get sorting list
        $arElementIDList = $this->obOfferListStore->getBySorting($sSorting);
        if(empty($arElementIDList)) {
            return $this->clear();
        }

        if($this->isClear()) {
            $this->arElementIDList = $arElementIDList;
            return $this;
        }

        $this->arElementIDList = array_intersect($arElementIDList, $this->arElementIDList);
        return $this->returnThis();
    }
    
    /**
     * Apply filter by active product list
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\OfferCollectionTest::testActiveList()
     * @return $this
     */
    public function active()
    {
        $arElementIDList = $this->obOfferListStore->getActiveList();
        return $this->intersect($arElementIDList);
    }
}