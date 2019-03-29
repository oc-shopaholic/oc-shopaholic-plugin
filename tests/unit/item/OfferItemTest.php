<?php namespace Lovata\Shopaholic\Tests\Unit\Item;

use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;
use Lovata\Toolbox\Classes\Helper\PriceHelper;
use Lovata\Toolbox\Models\Settings;
use Lovata\Toolbox\Tests\CommonTest;

/**
 * Class OfferItemTest
 * @package Lovata\Shopaholic\Tests\Unit\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class OfferItemTest extends CommonTest
{
    /** @var  Offer */
    protected $obElement;

    /** @var  Product */
    protected $obProduct;

    protected $arCreateData = [
        'name'         => 'name',
        'code'         => 'code',
        'preview_text' => 'preview_text',
        'description'  => 'description',
        'price'        => '10,50',
        'old_price'    => '11,50',
        'quantity'     => 5,
    ];

    protected $arProductData = [
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
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Offer item data is not correct';

        $arCreatedData = $this->arCreateData;
        $arCreatedData['id'] = $this->obElement->id;
        $arCreatedData['product_id'] = $this->obProduct->id;

        $arCreatedData['price'] = 10.50;
        $arCreatedData['old_price'] = 11.50;
        $arCreatedData['price_value'] = 10.50;
        $arCreatedData['old_price_value'] = 11.50;

        //Check item fields
        $obItem = OfferItem::make($this->obElement->id);
        foreach ($arCreatedData as $sField => $sValue) {
            self::assertEquals($sValue, $obItem->$sField, $sErrorMessage);
        }

        //Check product item data
        $obProductItem = $obItem->product;
        self::assertInstanceOf(ProductItem::class, $obProductItem);
        self::assertEquals($this->obProduct->id, $obProductItem->id, $sErrorMessage);
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

        $sErrorMessage = 'Offer item data is not correct, after model update';

        $obItem = OfferItem::make($this->obElement->id);
        self::assertEquals('name', $obItem->name, $sErrorMessage);

        //Check cache update
        $this->obElement->name = 'test';
        $this->obElement->save();

        $obItem = OfferItem::make($this->obElement->id);
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

        $sErrorMessage = 'Offer item data is not correct, after model delete';

        $obItem = OfferItem::make($this->obElement->id);
        self::assertEquals(false, $obItem->isEmpty(), $sErrorMessage);

        //Check active flag in item data
        $this->obElement->delete();

        $obItem = OfferItem::make($this->obElement->id);
        self::assertEquals(false, $obItem->isEmpty(), $sErrorMessage);
    }

    /**
     * Create test data
     */
    protected function createTestData()
    {
        Settings::set('decimals', 2);
        PriceHelper::forgetInstance();

        //Create product data
        $arCreateData = $this->arProductData;
        $arCreateData['active'] = true;
        $this->obProduct = Product::create($arCreateData);

        //Create new element data
        $arCreateData = $this->arCreateData;
        $arCreateData['active'] = true;
        $arCreateData['product_id'] = $this->obProduct->id;

        $this->obElement = Offer::create($arCreateData);
    }
}