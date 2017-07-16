<?php namespace Lovata\Shopaholic\Tests\Unit\Item;

include_once __DIR__.'/../../../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../../../tests/PluginTestCase.php';

use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Models\Brand;
use PluginTestCase;
use System\Classes\PluginManager;

/**
 * Class BrandItemTest
 * @package Lovata\Shopaholic\Tests\Unit\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class BrandItemTest extends PluginTestCase
{
    /** @var  Brand */
    protected $obElement;

    protected $arCreateData = [
        'name'         => 'name',
        'slug'         => 'slug',
        'code'         => 'code',
        'preview_text' => 'preview_text',
        'description'  => 'description',
    ];

    /**
     * Set up test method
     */
    public function setUp()
    {
        parent::setUp();

        $obManager = PluginManager::instance();
        $obManager->bootAll(true);
    }

    /**
     * Check item fields
     */
    public function testItemFields()
    {
        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Brand item data is not correct';

        $arCreatedData = $this->arCreateData;
        $arCreatedData['id'] = $this->obElement->id;

        //Check item fields
        $obItem = BrandItem::make($this->obElement->id);
        foreach ($arCreatedData as $sField => $sValue) {
            self::assertEquals($sValue, $obItem->$sField, $sErrorMessage);
        }
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

        $sErrorMessage = 'Brand item data is not correct, after model update';

        $obItem = BrandItem::make($this->obElement->id);
        self::assertEquals('name', $obItem->name, $sErrorMessage);

        //Check cache update
        $this->obElement->name = 'test';
        $this->obElement->save();

        $obItem = BrandItem::make($this->obElement->id);
        self::assertEquals('test', $obItem->name, $sErrorMessage);
    }

    /**
     * Check item data, after active flag = false
     */
    public function testActiveFlag()
    {
        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Brand item data is not correct, after model active flag = false';

        $obItem = BrandItem::make($this->obElement->id);
        self::assertEquals(false, $obItem->isEmpty(), $sErrorMessage);

        //Check active flag in item data
        $this->obElement->active = false;
        $this->obElement->save();

        $obItem = BrandItem::make($this->obElement->id);
        self::assertEquals(true, $obItem->isEmpty(), $sErrorMessage);
    }

    /**
     * Check update cache item data, after remove element
     */
    public function testRemoveElement()
    {
        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Brand item data is not correct, after model remove';

        $obItem = BrandItem::make($this->obElement->id);
        self::assertEquals(false, $obItem->isEmpty(), $sErrorMessage);

        //Remove element
        $this->obElement->delete();

        $obItem = BrandItem::make($this->obElement->id);
        self::assertEquals(true, $obItem->isEmpty(), $sErrorMessage);
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
    }
}