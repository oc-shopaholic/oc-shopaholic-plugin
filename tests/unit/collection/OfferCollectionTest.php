<?php namespace Lovata\Shopaholic\Tests\Unit\Collection;

use Lovata\Shopaholic\Classes\Collection\OfferCollection;
use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Toolbox\Tests\CommonTest;

/**
 * Class OfferCollectionTest
 * @package Lovata\Shopaholic\Tests\Unit\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class OfferCollectionTest extends CommonTest
{
    /** @var  Offer */
    protected $obElement;

    protected $arCreateData = [
        'name'         => 'name',
        'code'         => 'code',
        'preview_text' => 'preview_text',
        'description'  => 'description',
        'price'        => '10,50',
        'old_price'    => '11,50',
        'quantity'     => 5,
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

        $sErrorMessage = 'Offer collection item data is not correct';

        //Check item collection
        $obCollection = OfferCollection::make([$this->obElement->id]);

        /** @var OfferItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(OfferItem::class, $obItem, $sErrorMessage);
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

        $this->obElement = Offer::create($arCreateData);
    }
}