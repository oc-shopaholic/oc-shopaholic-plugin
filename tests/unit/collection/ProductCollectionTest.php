<?php namespace Lovata\Shopaholic\Tests\Unit\Collection;

include_once __DIR__.'/../../../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../../../tests/PluginTestCase.php';

use Lovata\Shopaholic\Classes\Collection\ProductCollection;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Models\Product;
use PluginTestCase;
use System\Classes\PluginManager;

/**
 * Class ProductCollectionTest
 * @package Lovata\Shopaholic\Tests\Unit\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class ProductCollectionTest extends PluginTestCase
{
    /** @var  Product */
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
    public function testCollectionItem()
    {
        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Product collection item data is not correct';

        //Check item fields
        $obCollection = ProductCollection::make([$this->obElement->id]);

        /** @var ProductItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(ProductItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);
    }

    /**
     * Create brand object for test
     */
    protected function createTestData()
    {
        //Create new element data
        $arCreateData = $this->arCreateData;
        $arCreateData['active'] = true;

        $this->obElement = Product::create($arCreateData);
    }
}