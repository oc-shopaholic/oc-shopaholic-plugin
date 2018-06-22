<?php namespace Lovata\Shopaholic\Tests\Unit\Item;

use Lovata\Shopaholic\Classes\Collection\OfferCollection;
use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;
use Lovata\Toolbox\Tests\CommonTest;

/**
 * Class ProductItemTest
 * @package Lovata\Shopaholic\Tests\Unit\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class ProductItemTest extends CommonTest
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
        'price'        => '10,50',
        'old_price'    => '11,50',
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
     * Check item fields
     */
    public function testItemFields()
    {
        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Product item data is not correct';

        $arCreatedData = $this->arCreateData;
        $arCreatedData['id'] = $this->obElement->id;
        $arCreatedData['category_id'] = $this->obCategory->id;
        $arCreatedData['brand_id'] = $this->obBrand->id;
        $arCreatedData['offer_id_list'] = [$this->obOffer->id];

        //Check item fields
        $obItem = ProductItem::make($this->obElement->id);
        foreach ($arCreatedData as $sField => $sValue) {
            self::assertEquals($sValue, $obItem->$sField, $sErrorMessage);
        }

        //Check category item data
        $obCategoryItem = $obItem->category;
        self::assertInstanceOf(CategoryItem::class, $obCategoryItem);
        self::assertEquals($this->obCategory->id, $obCategoryItem->id, $sErrorMessage);

        //Check brand item data
        $obBrandItem = $obItem->brand;
        self::assertInstanceOf(BrandItem::class, $obBrandItem);
        self::assertEquals($this->obBrand->id, $obBrandItem->id, $sErrorMessage);

        //Check offer collection
        $obOfferCollection = $obItem->offer;
        self::assertInstanceOf(OfferCollection::class, $obOfferCollection);

        /** @var OfferItem $obOfferItem */
        $obOfferItem = $obOfferCollection->first();
        self::assertInstanceOf(OfferItem::class, $obOfferItem);
        self::assertEquals($this->obOffer->id, $obOfferItem->id, $sErrorMessage);
    }

    /**
     * Check update cache item data, after update model data
     */
    public function testItemClearCache()
    {
        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Product item data is not correct, after model update';

        $obItem = ProductItem::make($this->obElement->id);
        self::assertEquals('name', $obItem->name, $sErrorMessage);

        //Check cache update
        $this->obElement->name = 'test';
        $this->obElement->save();

        $obItem = ProductItem::make($this->obElement->id);
        self::assertEquals('test', $obItem->name, $sErrorMessage);
    }

    /**
     * Check item data, after delete model
     */
    public function testDeleteElement()
    {
        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Product item data is not correct, after model delete';

        $obItem = ProductItem::make($this->obElement->id);
        self::assertEquals(false, $obItem->isEmpty(), $sErrorMessage);

        //Check active flag in item data
        $this->obElement->delete();

        $obItem = ProductItem::make($this->obElement->id);
        self::assertEquals(false, $obItem->isEmpty(), $sErrorMessage);
    }

    /**
     * Create test data
     */
    protected function createTestData()
    {
        //Create category data
        $this->obCategory = Category::create($this->arCategoryData);

        //Create brand data
        $this->obBrand = Brand::create($this->arBrandData);

        //Create product data
        $arCreateData = $this->arCreateData;
        $arCreateData['active'] = true;
        $arCreateData['category_id'] = $this->obCategory->id;
        $arCreateData['brand_id'] = $this->obBrand->id;
        $this->obElement = Product::create($arCreateData);

        //Create offer data
        $arCreateData = $this->arOfferData;
        $arCreateData['product_id'] = $this->obElement->id;
        $this->obOffer = Offer::create($arCreateData);
    }
}