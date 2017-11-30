<?php namespace Lovata\Shopaholic\Tests\Unit\Collection;

use Lovata\Shopaholic\Classes\Store\OfferListStore;
use Lovata\Toolbox\Tests\CommonTest;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Collection\OfferCollection;

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
        'price'        => 10.50,
        'old_price'    => 11.50,
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
     * Check item collection "active" method
     */
    public function testActiveList()
    {
        OfferCollection::make()->active();

        $this->createTestData();
        if(empty($this->obElement)) {
            return;
        }

        $sErrorMessage = 'Offer collection "active" method is not correct';

        //Check item collection after create
        $obCollection = OfferCollection::make()->active();

        /** @var OfferItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(OfferItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);

        $this->obElement->active = false;
        $this->obElement->save();

        //Check item collection, after active = false
        $obCollection = OfferCollection::make()->active();
        self::assertEquals(true, $obCollection->isEmpty(), $sErrorMessage);

        $this->obElement->active = true;
        $this->obElement->save();

        //Check item collection, after active = true
        $obCollection = OfferCollection::make()->active();

        /** @var OfferItem $obItem */
        $obItem = $obCollection->first();
        self::assertInstanceOf(OfferItem::class, $obItem, $sErrorMessage);
        self::assertEquals($this->obElement->id, $obItem->id, $sErrorMessage);

        $this->obElement->delete();

        //Check item collection, after element remove
        $obCollection = OfferCollection::make()->active();
        self::assertEquals(true, $obCollection->isEmpty(), $sErrorMessage);
    }

    /**
     * Check item collection "sort" method (price desc, asc)
     */
    public function testSortingByPrice()
    {
        $this->createTestData(1);
        $this->createTestData(2);
        if(empty($this->obElement)) {
            return;
        }

        OfferCollection::make()->sort(OfferListStore::SORT_PRICE_ASC);
        OfferCollection::make()->sort(OfferListStore::SORT_PRICE_DESC);

        $sErrorMessage = 'Offer collection "sort" method is not correct';

        //Check item collection after create
        $obCollection = OfferCollection::make()->sort(OfferListStore::SORT_PRICE_ASC);
        self::assertEquals([1,2], array_values($obCollection->getIDList()), $sErrorMessage);

        $obCollection = OfferCollection::make()->sort(OfferListStore::SORT_PRICE_DESC);
        self::assertEquals([2,1], array_values($obCollection->getIDList()), $sErrorMessage);

        //Check item collection, after update offer price
        $this->obElement->price = 1;
        $this->obElement->save();

        $obCollection = OfferCollection::make()->sort(OfferListStore::SORT_PRICE_DESC);
        self::assertEquals([1,2], array_values($obCollection->getIDList()), $sErrorMessage);

        $obCollection = OfferCollection::make()->sort(OfferListStore::SORT_PRICE_ASC);
        self::assertEquals([2,1], array_values($obCollection->getIDList()), $sErrorMessage);
    }
    
    /**
     * Create brand object for test
     * @param int $iCount
     */
    protected function createTestData($iCount= null)
    {
        //Create new element data
        $arCreateData = $this->arCreateData;
        $arCreateData['price'] = $iCount + $arCreateData['price'];
        $arCreateData['active'] = true;

        $this->obElement = Offer::create($arCreateData);
    }
}