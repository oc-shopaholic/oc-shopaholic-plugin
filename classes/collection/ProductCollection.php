<?php namespace Lovata\Shopaholic\Classes\Collection;

use Event;
use Lovata\Toolbox\Classes\Collection\ElementCollection;

use Lovata\Shopaholic\Models\PromoBlock;
use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
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
 * @method $this filterByPrice(float $fStartPrice, float $fStopPrice)
 * @method $this filterByBrandList(array $arBrandIDList)
 * @method $this filterByDiscount()
 * @method $this filterByQuantity()
 * @method $this filterByProperty(array $arFilterList, \Lovata\FilterShopaholic\Classes\Collection\FilterPropertyCollection $obPropertyList, OfferCollection $obOfferList = null)
 *
 * Tags for Shopaholic plugin
 * @method $this tag(int $iTagID)
 *
 * Discounts for Shopaholic plugin
 * @method $this discount(int $iDiscountID)
 *
 * Coupons for Shopaholic plugin
 * @method $this couponGroup(int $iCouponGroupID)
 *
 * Campaigns for Shopaholic plugin
 * @method $this campaign(int $iCampaignID)
 *
 * Search for Shopaholic, Sphinx for Shopaholic
 * @method $this search(string $sSearch)
 *
 * Compare for Shopaholic
 * @method $this compare()
 *
 * Wish list for Shopaholic
 * @method $this wishList()
 *
 * Viewed products for Shopaholic
 * @method $this viewed()
 *
 * Labels for Shopaholic
 * @method $this label($iLabelID)
 */
class ProductCollection extends ElementCollection
{
    const ITEM_CLASS = ProductItem::class;

    /**
     * Sort list by
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testSortingByID()
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testSortingByPrice()
     * @param string $sSorting
     * @return $this
     */
    public function sort($sSorting)
    {
        //Get sorting list
        $arResultIDList = ProductListStore::instance()->sorting->get($sSorting);

        return $this->applySorting($arResultIDList);
    }

    /**
     * Apply filter by active field
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testActiveList()
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testActiveListWithCheckingOffer()
     * @return $this
     */
    public function active()
    {
        $arResultIDList = ProductListStore::instance()->active->get();

        return $this->intersect($arResultIDList);
    }

    /**
     * Filter product list by category ID
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testCategoryFilter()
     * @param int|array $arCategoryIDList
     * @param bool $bWithChildren
     * @return $this
     */
    public function category($arCategoryIDList, $bWithChildren = false)
    {
        if (!is_array($arCategoryIDList)) {
            $arCategoryIDList = [$arCategoryIDList];
        }

        $arResultIDList = [];
        foreach ($arCategoryIDList as $iCategoryID) {
            $arResultIDList = array_merge($arResultIDList, (array) ProductListStore::instance()->category->get($iCategoryID));
            if ($bWithChildren) {
                $arResultIDList = array_merge($arResultIDList, (array) $this->getIDListChildrenCategory($iCategoryID));
            }
        }

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
        $arResultIDList = ProductListStore::instance()->brand->get($iBrandID);

        return $this->intersect($arResultIDList);
    }

    /**
     * Filter product list by promo block ID + different extensions
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testPromoFilter()
     * @param int $iPromoBlockID
     * @return $this
     */
    public function promo($iPromoBlockID)
    {
        $arResultIDList = ProductListStore::instance()->promo_block->get($iPromoBlockID);

        //Fire event, get additional product ID list
        $arEventDataList = Event::fire(PromoBlock::EVENT_GET_PRODUCT_LIST, $iPromoBlockID);
        if (empty($arEventDataList)) {
            return $this->intersect($arResultIDList);
        }

        //Process event data
        foreach ($arEventDataList as $arProductIDList) {
            if (empty($arProductIDList) || !is_array($arProductIDList)) {
                continue;
            }

            $arResultIDList = array_merge($arResultIDList, $arProductIDList);
        }

        $arResultIDList = array_unique($arResultIDList);

        return $this->intersect($arResultIDList);
    }

    /**
     * Filter product list by promo block ID
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\ProductCollectionTest::testPromoBlockFilter()
     * @param int $iPromoBlockID
     * @return $this
     */
    public function promoBlock($iPromoBlockID)
    {
        $arResultIDList = ProductListStore::instance()->promo_block->get($iPromoBlockID);

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
     * Get product ID list for children categories
     * @param int $iCategoryID
     * @return array
     */
    protected function getIDListChildrenCategory($iCategoryID) : array
    {
        //Get category item
        $obCategoryItem = CategoryItem::make($iCategoryID);
        if ($obCategoryItem->isEmpty() || $obCategoryItem->children->isEmpty()) {
            return [];
        }

        $arResultIDList = [];
        foreach ($obCategoryItem->children as $obChildCategoryItem) {
            $arResultIDList = array_merge($arResultIDList, (array) ProductListStore::instance()->category->get($obChildCategoryItem->id));
            $arResultIDList = array_merge($arResultIDList, $this->getIDListChildrenCategory($obChildCategoryItem->id));
        }

        return $arResultIDList;
    }
}
