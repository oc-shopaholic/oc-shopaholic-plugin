<?php namespace Lovata\Shopaholic\Classes\Event\Price;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Price;

use Lovata\Shopaholic\Classes\Store\OfferListStore;
use Lovata\Shopaholic\Classes\Store\ProductListStore;

/**
 * Class PriceModelHandler
 * @package Lovata\Shopaholic\Classes\Event\Price
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class PriceModelHandler
{
    protected $iPriority = 1000;
    /** @var  Price */
    protected $obElement;

    protected $obListStore;

    protected $bWithRestore = false;
    protected $sIdentifierField = 'id';

    /**
     * Add listeners
     */
    public function subscribe()
    {
        $sModelClass = $this->getModelClass();
        $sModelClass::extend(function ($obElement) {

            /** @var \Model $obElement */
            $obElement->bindEvent('model.afterSave', function () use ($obElement) {
                $this->obElement = $obElement;
                $this->afterSave();
            }, $this->iPriority);

            /** @var \Model $obElement */
            $obElement->bindEvent('model.afterDelete', function () use ($obElement) {
                $this->obElement = $obElement;
                $this->afterDelete();
            }, $this->iPriority);
        });
    }

    /**
     * After save event handler
     */
    protected function afterSave()
    {
        if ($this->obElement->getOriginal('price') != $this->obElement->price_value) {
            $this->clearPriceCache();
        }
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        $this->clearPriceCache();
    }

    /**
     * Clear product/offer price cache
     */
    protected function clearPriceCache()
    {
        $obItem = $this->obElement->item;
        if (empty($obItem)) {
            return;
        }

        if ($obItem instanceof Offer) {

            $sSorting = !empty($this->obElement->price_type) ? '|'.$this->obElement->price_type->code : '';

            OfferListStore::instance()->sorting->clear(OfferListStore::SORT_PRICE_ASC.$sSorting);
            OfferListStore::instance()->sorting->clear(OfferListStore::SORT_PRICE_DESC.$sSorting);

            if ($obItem->active) {
                ProductListStore::instance()->sorting->clear(ProductListStore::SORT_PRICE_ASC.$sSorting);
                ProductListStore::instance()->sorting->clear(ProductListStore::SORT_PRICE_DESC.$sSorting);
            }
        }
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return Price::class;
    }
}
