<?php namespace Lovata\Shopaholic\Tests\Unit\Collection;

use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Toolbox\Tests\CommonTest;

use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\Settings;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Classes\Store\ProductListStore;
use Lovata\Shopaholic\Classes\Collection\ProductCollection;

/**
 * Class ProductCollectionTest
 * @package Lovata\Shopaholic\Tests\Unit\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class ProductCollectionTest extends CommonTest
{
    /** @var  Product */
    protected $obElement;

    /** @var  Offer */
    protected $obOffer;

    /** @var  Brand */
    protected $obBrand;

    /** @var  Category */
    protected $obCategory;

    protected $arCreateData = [
        'name'         => 'name',
        'slug'         => 'slug',
        'code'         => 'code',
        'preview_text' => 'preview_text',
        'description'  => 'description',
    ];

    protected $arOfferData = [
        'active'       => true,
        'name'         => 'name',
        'code'         => 'code',
        'preview_text' => 'preview_text',
        'description'  => 'description',
        'price'        => 10.50,
        'old_price'    => 11.50,
        'quantity'     => 5,
    ];

    protected $arBrandData = [
        'active'       => true,
        'name'         => 'name',
        'slug'         => 'slug',
        'code'         => 'code',
        'preview_text' => 'preview_text',
        'description'  => 'description',
    ];

    protected $arCategoryData = [
        'active'       => true,
        'name'         => 'name',
        'slug'         => 'slug',
        'code'         => 'code',
        'preview_text' => 'preview_text',
        'description'  => 'description',
        'nest_depth'   => 0,
        'parent_id'    => 0,
    ];

    /**
     * Check item collection
     */
    public function testCollectionItem()
    {
        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Product collection item data is not correct';

        //Check item collection
        $obCollection = ProductCollection::make([$this->obElement->id]);

        /** @var ProductItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(ProductItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);
    }

    /**
     * Check item collection "active" method
     */
    public function testActiveList()
    {
        ProductCollection::make()->active();

        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Product collection "active" method is not correct';

        //Check item collection after create
        $obCollection = ProductCollection::make()->active();

        /** @var ProductItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(ProductItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);

        $this->obElement->active = false;
        $this->obElement->save();

        //Check item collection, after active = false
        $obCollection = ProductCollection::make()->active();
        self::assertEquals(true, $obCollection->isEmpty(), $sErrorMessage);

        $this->obElement->active = true;
        $this->obElement->save();

        //Check item collection, after active = true
        $obCollection = ProductCollection::make()->active();

        /** @var ProductItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(ProductItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);

        $this->obElement->delete();

        //Check item collection, after element remove
        $obCollection = ProductCollection::make()->active();
        self::assertEquals(true, $obCollection->isEmpty(), $sErrorMessage);
    }

    /**
     * Check item collection "active" method with checking offer
     */
    public function testActiveListWithCheckingOffer()
    {
        ProductCollection::make()->active();
        Settings::set('check_offer_active', true);

        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Product collection "active" method is not correct';

        //Check item collection after create
        $obCollection = ProductCollection::make()->active();

        /** @var ProductItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(ProductItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);

        $this->obOffer->active = false;
        $this->obOffer->save();

        //Check item collection, after active = false
        $obCollection = ProductCollection::make()->active();
        self::assertEquals(true, $obCollection->isEmpty(), $sErrorMessage);

        $this->obOffer->active = true;
        $this->obOffer->save();

        //Check item collection, after active = true
        $obCollection = ProductCollection::make()->active();

        /** @var ProductItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(ProductItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);

        $this->obOffer->delete();

        //Check item collection, after element remove
        $obCollection = ProductCollection::make()->active();
        self::assertEquals(true, $obCollection->isEmpty(), $sErrorMessage);

        Settings::set('check_offer_active', false);
    }

    /**
     * Check item collection "category" method
     */
    public function testCategoryFilter()
    {
        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        ProductCollection::make()->category($this->obCategory->id);

        $sErrorMessage = 'Product collection "category" method is not correct';

        //Check item collection after create
        $obCollection = ProductCollection::make()->category($this->obCategory->id);

        /** @var ProductItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(ProductItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);

        $this->obElement->category_id = $this->obCategory->id + 1;
        $this->obElement->save();

        //Check item collection, after change category_id field
        $obCollection = ProductCollection::make()->category($this->obCategory->id);
        self::assertEquals(true, $obCollection->isEmpty(), $sErrorMessage);

        $this->obElement->category_id = $this->obCategory->id;
        $this->obElement->save();

        //Check item collection, after change category_id field
        $obCollection = ProductCollection::make()->category($this->obCategory->id);

        /** @var ProductItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(ProductItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);

        $this->obElement->delete();

        //Check item collection, after element remove
        $obCollection = ProductCollection::make()->category($this->obCategory->id);
        self::assertEquals(true, $obCollection->isEmpty(), $sErrorMessage);
    }

    /**
     * Check item collection "category" method
     */
    public function testParentCategoryFilter()
    {
        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        ProductCollection::make()->category($this->obCategory->id);

        $sErrorMessage = 'Product collection "category" method is not correct';

        //Check item collection after create
        $obCollection = ProductCollection::make()->category($this->obCategory->id - 1, true);

        /** @var ProductItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(ProductItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);

        $this->obElement->category_id = $this->obCategory->id + 1;
        $this->obElement->save();

        //Check item collection, after change category_id field
        $obCollection = ProductCollection::make()->category($this->obCategory->id -1, true);
        self::assertEquals(true, $obCollection->isEmpty(), $sErrorMessage);

        $this->obElement->category_id = $this->obCategory->id;
        $this->obElement->save();

        //Check item collection, after change category_id field
        $obCollection = ProductCollection::make()->category($this->obCategory->id - 1, true);

        /** @var ProductItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(ProductItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);

        $this->obElement->delete();

        //Check item collection, after element remove
        $obCollection = ProductCollection::make()->category($this->obCategory->id -1, true);
        self::assertEquals(true, $obCollection->isEmpty(), $sErrorMessage);
    }

    /**
     * Check item collection "category" method
     */
    public function testMultiCategoryFilter()
    {
        $this->createTestData(1);
        $this->createTestData(2);
        if(empty($this->obElement)) {
            return;
        }

        ProductCollection::make()->category([$this->obCategory->id - 2, $this->obCategory->id]);

        $sErrorMessage = 'Product collection "category" method is not correct';

        //Check item collection after create
        $obCollection = ProductCollection::make()->category([$this->obCategory->id - 2, $this->obCategory->id]);

        self::assertEquals(2, $obCollection->count(), $sErrorMessage);
        self::assertEquals([$this->obElement->id - 1, $this->obElement->id], $obCollection->getIDList(), $sErrorMessage);

        $this->obElement->category_id = $this->obCategory->id + 1;
        $this->obElement->save();

        //Check item collection, after change category_id field
        $obCollection = ProductCollection::make()->category([$this->obCategory->id - 2, $this->obCategory->id]);

        self::assertEquals(1, $obCollection->count(), $sErrorMessage);
        self::assertEquals([$this->obElement->id - 1], $obCollection->getIDList(), $sErrorMessage);

        $this->obElement->category_id = $this->obCategory->id;
        $this->obElement->save();

        //Check item collection, after change category_id field
        $obCollection = ProductCollection::make()->category([$this->obCategory->id - 2, $this->obCategory->id]);

        self::assertEquals(2, $obCollection->count(), $sErrorMessage);
        self::assertEquals([$this->obElement->id - 1, $this->obElement->id], $obCollection->getIDList(), $sErrorMessage);

        $this->obElement->delete();

        $obCollection = ProductCollection::make()->category([$this->obCategory->id - 2, $this->obCategory->id]);

        //Check item collection, after element remove
        self::assertEquals(1, $obCollection->count(), $sErrorMessage);
        self::assertEquals([$this->obElement->id - 1], $obCollection->getIDList(), $sErrorMessage);
    }

    /**
     * Check item collection "brand" method
     */
    public function testBrandFilter()
    {
        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        ProductCollection::make()->brand($this->obBrand->id);

        $sErrorMessage = 'Product collection "brand" method is not correct';

        //Check item collection after create
        $obCollection = ProductCollection::make()->brand($this->obBrand->id);

        /** @var ProductItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(ProductItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);

        $this->obElement->brand_id = $this->obBrand->id + 1;
        $this->obElement->save();

        //Check item collection, after change brand_id field
        $obCollection = ProductCollection::make()->brand($this->obBrand->id);
        self::assertEquals(true, $obCollection->isEmpty(), $sErrorMessage);

        $this->obElement->brand_id = $this->obBrand->id;
        $this->obElement->save();

        //Check item collection, after change brand_id field
        $obCollection = ProductCollection::make()->brand($this->obBrand->id);

        /** @var ProductItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(ProductItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);

        $this->obElement->delete();

        //Check item collection, after element remove
        $obCollection = ProductCollection::make()->brand($this->obBrand->id);
        self::assertEquals(true, $obCollection->isEmpty(), $sErrorMessage);
    }

    /**
     * Check item collection "sort" method (default, new)
     */
    public function testSortingByID()
    {
        $this->createTestData(1);
        $this->createTestData(2);
        if(empty($this->obElement)) {
            return;
        }

        ProductCollection::make()->sort(ProductListStore::SORT_NO);
        ProductCollection::make()->sort(ProductListStore::SORT_NEW);

        $sErrorMessage = 'Product collection "sort" method is not correct';

        //Check item collection after create
        $obCollection = ProductCollection::make()->sort(ProductListStore::SORT_NO);
        self::assertEquals([1,2], array_values($obCollection->getIDList()), $sErrorMessage);

        $obCollection = ProductCollection::make()->sort(ProductListStore::SORT_NEW);
        self::assertEquals([2,1], array_values($obCollection->getIDList()), $sErrorMessage);

        $this->obElement->delete();

        //Check item collection, after element remove
        $obCollection = ProductCollection::make()->sort(ProductListStore::SORT_NO);
        self::assertEquals([1], array_values($obCollection->getIDList()), $sErrorMessage);

        $obCollection = ProductCollection::make()->sort(ProductListStore::SORT_NEW);
        self::assertEquals([1], array_values($obCollection->getIDList()), $sErrorMessage);
    }

    /**
     * Check item collection "sort" method (price desc, asc)
     */
    public function testSortingByPrice()
    {
        $this->createTestData(1);
        $this->createTestData(2);
        if(empty($this->obElement)) {
            return;
        }

        ProductCollection::make()->sort(ProductListStore::SORT_PRICE_ASC);
        ProductCollection::make()->sort(ProductListStore::SORT_PRICE_DESC);

        $sErrorMessage = 'Product collection "sort" method is not correct';

        //Check item collection after create
        $obCollection = ProductCollection::make()->sort(ProductListStore::SORT_PRICE_ASC);
        self::assertEquals([1,2], array_values($obCollection->getIDList()), $sErrorMessage);

        $obCollection = ProductCollection::make()->sort(ProductListStore::SORT_PRICE_DESC);
        self::assertEquals([2,1], array_values($obCollection->getIDList()), $sErrorMessage);

        //Check item collection, after update offer price
        $this->obOffer->price = 1;
        $this->obOffer->save();

        $obCollection = ProductCollection::make()->sort(ProductListStore::SORT_PRICE_DESC);
        self::assertEquals([1,2], array_values($obCollection->getIDList()), $sErrorMessage);

        $obCollection = ProductCollection::make()->sort(ProductListStore::SORT_PRICE_ASC);
        self::assertEquals([2,1], array_values($obCollection->getIDList()), $sErrorMessage);

        $this->obOffer->active = false;
        $this->obOffer->save();

        //Check item collection, after offer active = false
        $obCollection = ProductCollection::make()->sort(ProductListStore::SORT_PRICE_ASC);
        self::assertEquals([1], array_values($obCollection->getIDList()), $sErrorMessage);

        $obCollection = ProductCollection::make()->sort(ProductListStore::SORT_PRICE_DESC);
        self::assertEquals([1], array_values($obCollection->getIDList()), $sErrorMessage);

        //Check item collection, after offer active = true
        $this->obOffer->active = true;
        $this->obOffer->save();

        $obCollection = ProductCollection::make()->sort(ProductListStore::SORT_PRICE_DESC);
        self::assertEquals([1,2], array_values($obCollection->getIDList()), $sErrorMessage);

        $obCollection = ProductCollection::make()->sort(ProductListStore::SORT_PRICE_ASC);
        self::assertEquals([2,1], array_values($obCollection->getIDList()), $sErrorMessage);

        $this->obElement->delete();

        //Check item collection, after element remove
        $obCollection = ProductCollection::make()->sort(ProductListStore::SORT_PRICE_ASC);
        self::assertEquals([1], array_values($obCollection->getIDList()), $sErrorMessage);

        $obCollection = ProductCollection::make()->sort(ProductListStore::SORT_PRICE_DESC);
        self::assertEquals([1], array_values($obCollection->getIDList()), $sErrorMessage);
    }

    /**
     * Check item collection "getOfferMinPrice" method
     */
    public function testOfferMinPriceMethod()
    {
        $this->createTestData(1);
        $this->createTestData(2);
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Product collection "getOfferMinPrice" method is not correct';

        $obOfferItem = ProductCollection::make()->active()->getOfferMinPrice();

        self::assertInstanceOf(OfferItem::class, $obOfferItem, $sErrorMessage);
        self::assertEquals(1, $obOfferItem->id, $sErrorMessage);
    }

    /**
     * Check item collection "getOfferMaxPrice" method
     */
    public function testOfferMaxPriceMethod()
    {
        $this->createTestData(1);
        $this->createTestData(2);
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Product collection "getOfferMaxPrice" method is not correct';

        $obOfferItem = ProductCollection::make()->active()->getOfferMaxPrice();

        self::assertInstanceOf(OfferItem::class, $obOfferItem, $sErrorMessage);
        self::assertEquals(2, $obOfferItem->id, $sErrorMessage);
    }

    /**
     * Create brand object for test
     * @param int $iCount
     */
    protected function createTestData($iCount= null)
    {
        //Create category data
        $arCreateData = $this->arCategoryData;
        $arCreateData['slug'] = $arCreateData['slug'].'_parent_'.$iCount;
        $this->obCategory = Category::create($arCreateData);

        $arCreateData = $this->arCategoryData;
        $arCreateData['slug'] = $arCreateData['slug'].$iCount;
        $this->obCategory = Category::create($arCreateData);
        $this->obCategory->parent_id = $this->obCategory->id -1;
        $this->obCategory->save();

        //Create brand data
        $arCreateData = $this->arBrandData;
        $arCreateData['slug'] = $arCreateData['slug'].$iCount;
        $this->obBrand = Brand::create($arCreateData);

        //Create product data
        $arCreateData = $this->arCreateData;
        $arCreateData['active'] = true;
        $arCreateData['slug'] = $arCreateData['slug'].$iCount;
        $arCreateData['category_id'] = $this->obCategory->id;
        $arCreateData['brand_id'] = $this->obBrand->id;
        $this->obElement = Product::create($arCreateData);

        //Create offer data
        $arCreateData = $this->arOfferData;
        $arCreateData['product_id'] = $this->obElement->id;
        $arCreateData['price'] = $iCount + $arCreateData['price'];
        $this->obOffer = Offer::create($arCreateData);
    }
}