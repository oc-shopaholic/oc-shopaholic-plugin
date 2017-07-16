<?php namespace Lovata\Shopaholic\Tests\Unit\Collection;

include_once __DIR__.'/../../../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../../../tests/PluginTestCase.php';

use Lovata\Shopaholic\Classes\Collection\BrandCollection;
use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Models\Brand;
use PluginTestCase;
use System\Classes\PluginManager;

/**
 * Class BrandCollectionTest
 * @package Lovata\Shopaholic\Tests\Unit\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class BrandCollectionTest extends PluginTestCase
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
    public function testCollectionItem()
    {
        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Brand collection item data is not correct';

        //Check item fields
        $obCollection = BrandCollection::make([$this->obElement->id]);

        /** @var BrandItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(BrandItem::class, $obItem, $sErrorMessage);
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

        $this->obElement = Brand::create($arCreateData);
    }
}