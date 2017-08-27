<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Toolbox\Classes\Collection\ElementCollection;

use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Classes\Store\OfferListStore;
use Lovata\Shopaholic\Classes\Store\ProductListStore;

/**
 * Class ProductCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductCollection extends ElementCollection
{
    /** @var ProductListStore */
    protected $obProductListStore;

    /**
     * ProductCollection constructor.
     * @param ProductListStore $obProductListStore
     */
    public function __construct(ProductListStore $obProductListStore)
    {
        $this->obProductListStore = $obProductListStore;
        parent::__construct();
    }

    /**
     * Make element item
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testCollectionItem()
     * @param int                               $iElementID
     * @param \Lovata\Shopaholic\Models\Product $obElement
     *
     * @return ProductItem
     */
    protected function makeItem($iElementID, $obElement = null)
    {
        return ProductItem::make($iElementID, $obElement);
    }

    /**
     * Sort list by
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testSortingByID()
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testSortingByPrice()
     * @param string $sSorting
     * @return $this
     */
    public function sort($sSorting)
    {
        if(!$this->isClear() && $this->isEmpty()) {
            return $this;
        }

        //Get sorting list
        $arElementIDList = $this->obProductListStore->getBySorting($sSorting);
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
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testActiveList()
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testActiveListWithCheckingOffer()
     * @return $this
     */
    public function active()
    {
        $arElementIDList = $this->obProductListStore->getActiveList();
        return $this->intersect($arElementIDList);
    }

    /**
     * Filter product list by category ID
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testCategoryFilter()
     * @param int $iCategoryID
     * @return $this
     */
    public function category($iCategoryID)
    {
        $arElementIDList = $this->obProductListStore->getByCategory($iCategoryID);
        return $this->intersect($arElementIDList);
    }

    /**
     * Filter product list by category ID
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testBrandFilter()
     * @param int $iBrandID
     * @return $this
     */
    public function brand($iBrandID)
    {
        $arElementIDList = $this->obProductListStore->getByBrand($iBrandID);
        return $this->intersect($arElementIDList);
    }

    /**
     * Get offer with min price
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testOfferMinPriceMethod()
     * @return OfferItem
     */
    public function getOfferMinPrice()
    {
        $obProductList = clone $this;
        $obProductList->sort(ProductListStore::SORT_PRICE_ASC);

        //Get product with min price
        /** @var \Lovata\Shopaholic\Classes\Item\ProductItem $obProductItem */
        $obProductItem = $obProductList->first();

        if($obProductItem->isEmpty()) {
            return OfferItem::make(null);
        }

        //Get offer with min price
        $obOfferCollection = $obProductItem->offer;
        if($obOfferCollection->isEmpty()) {
            return OfferItem::make(null);
        }

        /** @var OfferItem $obOfferItem */
        $obOfferItem = $obOfferCollection->active()->sort(OfferListStore::SORT_PRICE_ASC)->first();

        return $obOfferItem;
    }

    /**
     * Get offer with max price
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testOfferMaxPriceMethod()
     * @return OfferItem
     */
    public function getOfferMaxPrice()
    {
        $obProductList = clone $this;
        $obProductList->sort(ProductListStore::SORT_PRICE_ASC);

        //Get product with min price
        /** @var \Lovata\Shopaholic\Classes\Item\ProductItem $obProductItem */
        $obProductItem = $obProductList->last();

        if($obProductItem->isEmpty()) {
            return OfferItem::make(null);
        }

        //Get offer with min price
        $obOfferCollection = $obProductItem->offer;
        if($obOfferCollection->isEmpty()) {
            return OfferItem::make(null);
        }

        /** @var OfferItem $obOfferItem */
        $obOfferItem = $obOfferCollection->active()->sort(OfferListStore::SORT_PRICE_ASC)->last();

        return $obOfferItem;
    }
}