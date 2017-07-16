<?php namespace Lovata\Shopaholic\Tests\Unit\Collection;

include_once __DIR__.'/../../../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../../../tests/PluginTestCase.php';

use Lovata\Shopaholic\Classes\Collection\CategoryCollection;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Models\Category;
use PluginTestCase;
use System\Classes\PluginManager;

/**
 * Class CategoryCollectionTest
 * @package Lovata\Shopaholic\Tests\Unit\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class CategoryCollectionTest extends PluginTestCase
{
    /** @var  Category */
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

        $sErrorMessage = 'Category collection item data is not correct';

        //Check item fields
        $obCollection = CategoryCollection::make([$this->obElement->id]);

        /** @var CategoryItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(CategoryItem::class, $obItem, $sErrorMessage);
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

        $this->obElement = Category::create($arCreateData);
    }
}