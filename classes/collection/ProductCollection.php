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
 *
 * @link https://github.com/lovata/oc-shopaholic-plugin/wiki/ProductCollection
 *
 * Filter for Shopaholic plugin
 * @method static $this filterByPrice(float $fStartPrice, float $fStopPrice)
 * @method static $this filterByBrandList(array $arBrandIDList)
 * @method static $this filterByDiscount()
 * @method static $this filterByQuantity()
 * @method static $this filterByProperty(array $arFilterList, \Lovata\FilterShopaholic\Classes\Collection\FilterPropertyCollection $obPropertyList)
 *
 * Tags for Shopaholic plugin
 * @method static $this tag(int $iTagID)
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
     * Sort list by
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testSortingByID()
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testSortingByPrice()
     * @param string $sSorting
     * @return $this
     */
    public function sort($sSorting)
    {
        if (!$this->isClear() && $this->isEmpty()) {
            return $this->returnThis();
        }

        //Get sorting list
        $arResultIDList = $this->obProductListStore->getBySorting($sSorting);
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
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testActiveList()
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testActiveListWithCheckingOffer()
     * @return $this
     */
    public function active()
    {
        $arResultIDList = $this->obProductListStore->getActiveList();

        return $this->intersect($arResultIDList);
    }

    /**
     * Filter product list by category ID
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testCategoryFilter()
     * @param int $iCategoryID
     * @return $this
     */
    public function category($iCategoryID)
    {
        $arResultIDList = $this->obProductListStore->getByCategory($iCategoryID);

        return $this->intersect($arResultIDList);
    }

    /**
     * Filter product list by category ID
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testBrandFilter()
     * @param int $iBrandID
     * @return $this
     */
    public function brand($iBrandID)
    {
        $arResultIDList = $this->obProductListStore->getByBrand($iBrandID);

        return $this->intersect($arResultIDList);
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

        if ($obProductItem->isEmpty()) {
            return OfferItem::make(null);
        }

        //Get offer with min price
        $obOfferCollection = $obProductItem->offer;
        if ($obOfferCollection->isEmpty()) {
            return OfferItem::make(null);
        }

        /** @var OfferItem $obOfferItem */
        $obOfferItem = $obOfferCollection->sort(OfferListStore::SORT_PRICE_ASC)->first();

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

        if ($obProductItem->isEmpty()) {
            return OfferItem::make(null);
        }

        //Get offer with min price
        $obOfferCollection = $obProductItem->offer;
        if ($obOfferCollection->isEmpty()) {
            return OfferItem::make(null);
        }

        /** @var OfferItem $obOfferItem */
        $obOfferItem = $obOfferCollection->sort(OfferListStore::SORT_PRICE_ASC)->last();

        return $obOfferItem;
    }

    /**
     * Make element item
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testCollectionItem()
     *
     * @param int                               $iElementID
     * @param \Lovata\Shopaholic\Models\Product $obElement
     *
     * @return ProductItem
     */
    protected function makeItem($iElementID, $obElement = null)
    {
        return ProductItem::make($iElementID, $obElement);
    }
}
