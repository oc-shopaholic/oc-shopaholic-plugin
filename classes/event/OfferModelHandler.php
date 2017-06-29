<?php namespace Lovata\Shopaholic\Classes\Event;

use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Classes\Store\ProductListStore;
use Lovata\Shopaholic\Models\Offer;

/**
 * Class OfferModelHandler
 * @package Lovata\Shopaholic\Classes\Event
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class OfferModelHandler
{
    /** @var  \Lovata\Shopaholic\Models\Offer */
    protected $obElement;

    /** @var  ProductListStore */
    protected $obProductListStore;

    public function __construct(ProductListStore $obProductListStore)
    {
        $this->obProductListStore = $obProductListStore;
    }

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        $obEvent->listen('shopaholic.offer.after.save', OfferModelHandler::class.'@afterSave');
        $obEvent->listen('shopaholic.offer.after.delete', OfferModelHandler::class.'@afterDelete');
    }

    /**
     * After save event handler
     * @param \Lovata\Shopaholic\Models\Offer $obElement
     */
    public function afterSave($obElement)
    {
        if(empty($obElement) || !$obElement instanceof Offer) {
            return;
        }

        $this->obElement = $obElement;
        $this->clearItemCache();

        $this->checkProductIDField();
        $this->checkPriceField();
    }

    /**
     * After delete event handler
     * @param \Lovata\Shopaholic\Models\Offer $obElement
     */
    public function afterDelete($obElement)
    {
        if(empty($obElement) || !$obElement instanceof Offer) {
            return;
        }

        $this->obElement = $obElement;

        $this->clearItemCache();
        $this->clearProductItemCache($obElement->product_id);

        //Get product object
        $obProduct = $obElement->product;
        if(empty($obProduct) || !$obElement->active) {
            return;
        }

        $this->obProductListStore->updateCacheBySorting(ProductListStore::SORT_PRICE_ASC);
        $this->obProductListStore->updateCacheBySorting(ProductListStore::SORT_PRICE_DESC);
    }

    /**
     * Clear item cache
     */
    protected function clearItemCache()
    {
        OfferItem::clearCache($this->obElement->id);
    }

    /**
     * Clear product item cache
     * @param int $iProductID
     */
    protected function clearProductItemCache($iProductID)
    {
        ProductItem::clearCache($iProductID);
    }

    /**
     * Clear cache, if product ID field changed
     */
    protected function checkProductIDField()
    {
        //Clear product cache
        /** @var int $iOriginalProductID */
        $iOriginalProductID = $this->obElement->getOriginal('product_id');

        /** @var int $iProductID */
        $iProductID =  $this->obElement->getAttribute('product_id');

        if($iOriginalProductID == $iProductID) {
            return;
        }

        if(!empty($iOriginalProductID)) {
            $this->clearProductItemCache($iOriginalProductID);
        }

        if(!empty($iProductID)) {
            $this->clearProductItemCache($iOriginalProductID);
        }
    }

    /**
     * Clear cache, if price field changed
     */
    protected function checkPriceField()
    {
        $bNeedUpdateFlag =
            $this->obElement->getOriginal('active') != $this->obElement->active
            || (
                $this->obElement->active
                && $this->obElement->getOriginal('active') == $this->obElement->active
                && $this->obElement->getOriginal('price') != $this->obElement->price
            );

        if(!$bNeedUpdateFlag) {
            return;
        }

        //Get product object
        $obProduct = $this->obElement->product;
        if(empty($obProduct)) {
            return;
        }

        $this->obProductListStore->updateCacheBySorting(ProductListStore::SORT_PRICE_ASC);
        $this->obProductListStore->updateCacheBySorting(ProductListStore::SORT_PRICE_DESC);
    }
} 