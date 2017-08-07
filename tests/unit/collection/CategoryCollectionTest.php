<?php namespace Lovata\Shopaholic\Tests\Unit\Collection;

use Lovata\Toolbox\Tests\CommonTest;

use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Collection\CategoryCollection;

/**
 * Class CategoryCollectionTest
 * @package Lovata\Shopaholic\Tests\Unit\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class CategoryCollectionTest extends CommonTest
{
    /** @var  Category */
    protected $obElement;

    /** @var  Category */
    protected $obChildElement;

    protected $arCreateData = [
        'name'         => 'name',
        'slug'         => 'slug',
        'code'         => 'code',
        'preview_text' => 'preview_text',
        'description'  => 'description',
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

        $sErrorMessage = 'Category collection item data is not correct';

        //Check item collection
        $obCollection = CategoryCollection::make([$this->obElement->id]);

        /** @var CategoryItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(CategoryItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);
    }

    /**
     * Check item collection "tree" method
     */
    public function testTreeMethod()
    {
        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Category collection "tree" method is not correct';

        //Check item collection
        $obCollection = CategoryCollection::make()->tree();

        /** @var CategoryItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(CategoryItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);
        self::assertEquals(1, $obCollection->count(), $sErrorMessage);
    }

    /**
     * Create brand object for test
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
    }
}