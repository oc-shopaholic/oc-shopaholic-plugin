<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Toolbox\Classes\Collection\ElementCollection;

use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Store\OfferListStore;

/**
 * Class OfferCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @see \Lovata\Shopaholic\Tests\Unit\Collection\OfferCollectionTest
 * @link https://github.com/lovata/oc-shopaholic-plugin/wiki/OfferCollection
 *
 * Filter for Shopaholic plugin
 * @method static $this filterByPrice(float $fStartPrice, float $fStopPrice)
 * @method static $this filterByProperty(array $arFilterList, \Lovata\FilterShopaholic\Classes\Collection\FilterPropertyCollection $obPropertyList)
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
     * Sort list by
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\OfferCollectionTest::testSortingByPrice()
     * @param string $sSorting
     * @return $this
     */
    public function sort($sSorting)
    {
        if (!$this->isClear() && $this->isEmpty()) {
            return $this->returnThis();
        }

        //Get sorting list
        $arResultIDList = $this->obOfferListStore->getBySorting($sSorting);
        if (empty($arResultIDList)) {
            return $this->clear();
        }

        if ($this->isClear()) {
            $this->arElementIDList = $arResultIDList;

            return $this->returnThis();
        }

        $this->arElementIDList = array_intersect($arResultIDList, $this->arElementIDList);

        return $this->returnThis();
    }

    /**
     * Apply filter by active product list
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\OfferCollectionTest::testActiveList()
     * @return $this
     */
    public function active()
    {
        $arResultIDList = $this->obOfferListStore->getActiveList();

        return $this->intersect($arResultIDList);
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
}
