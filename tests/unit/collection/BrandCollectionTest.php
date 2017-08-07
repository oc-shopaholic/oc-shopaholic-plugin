<?php namespace Lovata\Shopaholic\Tests\Unit\Collection;

use Lovata\Toolbox\Tests\CommonTest;

use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Classes\Collection\BrandCollection;

/**
 * Class BrandCollectionTest
 * @package Lovata\Shopaholic\Tests\Unit\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class BrandCollectionTest extends CommonTest
{
    /** @var  Brand */
    protected $obElement;

    /** @var  Category */
    protected $obCategory;

    /** @var  Product */
    protected $obProduct;

    /** @var  Offer */
    protected $obOffer;

    protected $arCreateData = [
        'name'         => 'name',
        'slug'         => 'slug',
        'code'         => 'code',
        'preview_text' => 'preview_text',
        'description'  => 'description',
    ];

    protected $arProductData = [
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

        $sErrorMessage = 'Brand collection item data is not correct';

        //Check item collection
        $obCollection = BrandCollection::make([$this->obElement->id]);

        /** @var BrandItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(BrandItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);
    }

    /**
     * Check item collection "active" method
     */
    public function testActiveList()
    {
        BrandCollection::make()->active();

        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Brand collection "active" method is not correct';

        //Check item collection after create
        $obCollection = BrandCollection::make()->active();

        /** @var BrandItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(BrandItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);

        $this->obElement->active = false;
        $this->obElement->save();

        //Check item collection, after active = false
        $obCollection = BrandCollection::make()->active();
        self::assertEquals(true, $obCollection->isEmpty(), $sErrorMessage);

        $this->obElement->active = true;
        $this->obElement->save();

        //Check item collection, after active = true
        $obCollection = BrandCollection::make()->active();

        /** @var BrandItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(BrandItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);

        $this->obElement->delete();

        //Check item collection, after element remove
        $obCollection = BrandCollection::make()->active();
        self::assertEquals(true, $obCollection->isEmpty(), $sErrorMessage);
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

        BrandCollection::make()->category($this->obCategory->id);

        $sErrorMessage = 'Brand collection "category" method is not correct';

        //Check item collection after create
        $obCollection = BrandCollection::make()->category($this->obCategory->id);

        /** @var BrandItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(BrandItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);

        $this->obProduct->category_id = $this->obCategory->id + 1;
        $this->obProduct->save();

        //Check item collection, after change category_id field
        $obCollection = BrandCollection::make()->category($this->obCategory->id);
        self::assertEquals(true, $obCollection->isEmpty(), $sErrorMessage);

        $this->obProduct->category_id = $this->obCategory->id;
        $this->obProduct->save();

        //Check item collection, after change category_id field
        $obCollection = BrandCollection::make()->category($this->obCategory->id);

        /** @var BrandItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(BrandItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);

        $this->obProduct->delete();

        //Check item collection, after element remove
        $obCollection = BrandCollection::make()->category($this->obCategory->id);
        self::assertEquals(true, $obCollection->isEmpty(), $sErrorMessage);
    }
    
    /**
     * Create brand object for test
     */
    protected function createTestData()
    {
        //Create new element data
        $arCreateData = $this->arCreateData;
        $arCreateData['active'] = true;

        $this->obElement = Brand::create($arCreateData);


        //Create category data
        $arCreateData = $this->arCategoryData;
        $this->obCategory = Category::create($arCreateData);

        //Create product data
        $arCreateData = $this->arCreateData;
        $arCreateData['active'] = true;
        $arCreateData['category_id'] = $this->obCategory->id;
        $arCreateData['brand_id'] = $this->obElement->id;
        $this->obProduct = Product::create($arCreateData);

        //Create offer data
        $arCreateData = $this->arOfferData;
        $arCreateData['product_id'] = $this->obProduct->id;
        $this->obOffer = Offer::create($arCreateData);
    }
}