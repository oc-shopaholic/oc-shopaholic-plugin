<?php namespace Lovata\Shopaholic\Tests\Unit\Item;

use Lovata\Shopaholic\Classes\Collection\ProductCollection;
use Lovata\Toolbox\Tests\CommonTest;

use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Collection\CategoryCollection;

/**
 * Class CategoryItemTest
 * @package Lovata\Shopaholic\Tests\Unit\Item
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class CategoryItemTest extends CommonTest
{
    /** @var  Category */
    protected $obElement;

    /** @var  Category */
    protected $obChildElement;

    /** @var Product */
    protected $obProduct;

    protected $arCreateData = [
        'name'         => 'name',
        'slug'         => 'slug',
        'code'         => 'code',
        'preview_text' => 'preview_text',
        'description'  => 'description',
    ];

    protected $arProductData = [
        'active'       => true,
        'name'         => 'name',
        'slug'         => 'slug',
        'code'         => 'code',
        'preview_text' => 'preview_text',
        'description'  => 'description',
    ];

    /**
     * Check item fields
     */
    public function testItemFields()
    {
        $this->createTestData();
        if (empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Category item data is not correct';

        $arCreatedData = $this->arCreateData;
        $arCreatedData['id'] = $this->obElement->id;
        $arCreatedData['nest_depth'] = 0;
        $arCreatedData['parent_id'] = 0;
        $arCreatedData['children_id_list'] = [$this->obChildElement->id];

        //Check item fields
        $obItem = CategoryItem::make($this->obElement->id);
        foreach ($arCreatedData as $sField => $sValue) {
            self::assertEquals($sValue, $obItem->$sField, $sErrorMessage);
        }

        if (empty($this->obChildElement)) {
            return;
        }

        $arCreatedData = $this->arCreateData;
        $arCreatedData['id'] = $this->obChildElement->id;
        $arCreatedData['slug'] = 'slug1';
        $arCreatedData['nest_depth'] = 1;
        $arCreatedData['parent_id'] = $this->obElement->id;

        $obChildrenCollection = $obItem->children;
        self::assertNotEmpty($obChildrenCollection, $sErrorMessage);
        self::assertInstanceOf(CategoryCollection::class, $obChildrenCollection, $sErrorMessage);

        /** @var CategoryItem $obChildItem */
        $obChildItem = $obChildrenCollection->shift();

        //Check item fields
        foreach ($arCreatedData as $sField => $sValue) {
            self::assertEquals($sValue, $obChildItem->$sField, $sErrorMessage);
        }
    }

    /**
     * Check update cache item data, after update model data
     */
    public function testItemClearCache()
    {
        $this->createTestData();
        if (empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Category item data is not correct, after model update';

        $obItem = CategoryItem::make($this->obElement->id);
        self::assertEquals('name', $obItem->name, $sErrorMessage);

        //Check cache update
        $this->obElement->name = 'test';
        $this->obElement->save();

        $obItem = CategoryItem::make($this->obElement->id);
        self::assertEquals('test', $obItem->name, $sErrorMessage);
    }

    /**
     * Check update cache item data, after remove element
     */
    public function testRemoveElement()
    {
        $this->createTestData();
        if (empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Category item data is not correct, after model remove';

        $obItem = CategoryItem::make($this->obChildElement->id);
        self::assertEquals(false, $obItem->isEmpty(), $sErrorMessage);

        //Remove element
        $this->obChildElement->delete();

        $obItem = CategoryItem::make($this->obChildElement->id);
        self::assertEquals(true, $obItem->isEmpty(), $sErrorMessage);

        $obItem = CategoryItem::make($this->obElement->id);
        $obChildCollection = $obItem->children;
        self::assertEquals(true, $obChildCollection->isEmpty(), $sErrorMessage);
    }

    /**
     * Test product_count field for main category
     */
    public function testProductCountField()
    {
        $this->createTestData();
        if (empty($this->obElement)) {
            return;
        }

        $obParentItem = CategoryItem::make($this->obElement->id);
        $obItem = CategoryItem::make($this->obChildElement->id);

        self::assertEquals(1, $obParentItem->product_count);
        self::assertEquals(1, $obItem->product_count);

        //Set empty category_id in Product object
        $this->obProduct->category_id = null;
        $this->obProduct->save();

        $obParentItem = CategoryItem::make($this->obElement->id);
        $obItem = CategoryItem::make($this->obChildElement->id);

        self::assertEquals(0, $obParentItem->product_count);
        self::assertEquals(0, $obItem->product_count);

        //Set parent category_id in Product object
        $this->obProduct->category_id = $this->obElement->id;
        $this->obProduct->save();

        $obParentItem = CategoryItem::make($this->obElement->id);
        $obItem = CategoryItem::make($this->obChildElement->id);

        self::assertEquals(1, $obParentItem->product_count);
        self::assertEquals(0, $obItem->product_count);

        //Set child category_id in Product object
        $this->obProduct->category_id = $this->obChildElement->id;
        $this->obProduct->save();

        $obParentItem = CategoryItem::make($this->obElement->id);
        $obItem = CategoryItem::make($this->obChildElement->id);

        self::assertEquals(1, $obParentItem->product_count);
        self::assertEquals(1, $obItem->product_count);

        //Set active == false in Product object
        $this->obProduct->active = false;
        $this->obProduct->save();

        ProductCollection::make()->active()->save(CategoryItem::class.'_active');
        $obParentItem = CategoryItem::make($this->obElement->id);
        $obItem = CategoryItem::make($this->obChildElement->id);

        self::assertEquals(0, $obParentItem->product_count);
        self::assertEquals(0, $obItem->product_count);

        //Set active == true in Product object
        $this->obProduct->active = true;
        $this->obProduct->save();

        ProductCollection::make()->active()->save(CategoryItem::class.'_active');
        $obParentItem = CategoryItem::make($this->obElement->id);
        $obItem = CategoryItem::make($this->obChildElement->id);

        self::assertEquals(1, $obParentItem->product_count);
        self::assertEquals(1, $obItem->product_count);
    }

    /**
     * Create data for test
     */
    protected function createTestData()
    {
        //Create new element data
        $arCreatedData = $this->arCreateData;
        $arCreatedData['active'] = true;

        $this->obElement = Category::create($arCreatedData);

        $arCreatedData = $this->arCreateData;
        $arCreatedData['active'] = true;
        $arCreatedData['slug'] = 'slug1';

        $this->obChildElement = Category::create($arCreatedData);

        $this->obChildElement->parent_id = $this->obElement->id;
        $this->obChildElement->nest_depth = 1;
        $this->obChildElement->save();

        $arProductData = $this->arProductData;
        $arProductData['category_id'] = $this->obChildElement->id;

        $this->obProduct = Product::create($arProductData);
    }
}
