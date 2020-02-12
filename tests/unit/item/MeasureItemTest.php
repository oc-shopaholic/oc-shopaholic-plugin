<?php namespace Lovata\Shopaholic\Tests\Unit\Item;

use Lovata\Toolbox\Tests\CommonTest;

use Lovata\Shopaholic\Models\Measure;
use Lovata\Shopaholic\Classes\Item\MeasureItem;

/**
 * Class MeasureItemTest
 * @package Lovata\Shopaholic\Tests\Unit\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class MeasureItemTest extends CommonTest
{
    /** @var  Measure */
    protected $obElement;

    protected $arCreateData = [
        'name' => 'name',
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

        $sErrorMessage = 'Measure item data is not correct';

        $arCreatedData = $this->arCreateData;
        $arCreatedData['id'] = $this->obElement->id;

        //Check item fields
        $obItem = MeasureItem::make($this->obElement->id);
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

        $sErrorMessage = 'Measure item data is not correct, after model update';

        $obItem = MeasureItem::make($this->obElement->id);
        self::assertEquals('name', $obItem->name, $sErrorMessage);

        //Check cache update
        $this->obElement->name = 'test';
        $this->obElement->save();

        $obItem = MeasureItem::make($this->obElement->id);
        self::assertEquals('test', $obItem->name, $sErrorMessage);
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

        $sErrorMessage = 'Measure item data is not correct, after model remove';

        $obItem = MeasureItem::make($this->obElement->id);
        self::assertEquals(false, $obItem->isEmpty(), $sErrorMessage);

        //Remove element
        $this->obElement->delete();

        $obItem = MeasureItem::make($this->obElement->id);
        self::assertEquals(true, $obItem->isEmpty(), $sErrorMessage);
    }

    /**
     * Create measure object for test
     */
    protected function createTestData()
    {
        //Create new element data
        $arCreateData = $this->arCreateData;

        $this->obElement = Measure::create($arCreateData);
    }
}