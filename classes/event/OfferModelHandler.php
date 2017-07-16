<?php namespace Lovata\Shopaholic\Classes\Event;

use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Classes\Store\OfferListStore;
use Lovata\Shopaholic\Classes\Store\ProductListStore;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Plugin;

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

    /** @var  OfferListStore */
    protected $obOfferListStore;

    public function __construct(ProductListStore $obProductListStore, OfferListStore $obOfferListStore)
    {
        $this->obProductListStore = $obProductListStore;
        $this->obOfferListStore = $obOfferListStore;
    }

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        Offer::extend(function ($obElement) {
            /** @var Offer $obElement */
            $obElement->bindEvent('model.afterSave', function () use($obElement) {
                $this->afterSave($obElement);
            });
        });

        Offer::extend(function ($obElement) {
            /** @var Offer $obElement */
            $obElement->bindEvent('model.afterDelete', function () use($obElement) {
                $this->afterDelete($obElement);
            });
        });
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

        //Check "active" flag
        $this->checkActiveField();
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

        if($obElement->active) {
            $this->removeFromActiveList();
        }

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

    /**
     * Check offer "active" field, if it was changed, then clear cache
     */
    private function checkActiveField()
    {
        //check product "active" field
        if($this->obElement->getOriginal('active') == $this->obElement->active) {
            return;
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, OfferListStore::CACHE_TAG_LIST];
        $sCacheKey = OfferListStore::CACHE_TAG_LIST;

        //Clear cache data
        CCache::clear($arCacheTags, $sCacheKey);
        $this->obOfferListStore->getActiveList();
    }

    /**
     * Remove offer from active product ID list
     */
    private function removeFromActiveList()
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, OfferListStore::CACHE_TAG_LIST];
        $sCacheKey = OfferListStore::CACHE_TAG_LIST;

        //Check cache array
        $arOfferIDList = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arOfferIDList)) {
            $this->obOfferListStore->getActiveList();
            return;
        }

        if(!in_array($this->obElement->id, $arOfferIDList)) {
            return;
        }

        //Remove element from cache array and save
        $iPosition = array_search($this->obElement->id, $arOfferIDList);
        if($iPosition === false) {
            return;
        }

        unset($arOfferIDList[$iPosition]);

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arOfferIDList);
    }
}