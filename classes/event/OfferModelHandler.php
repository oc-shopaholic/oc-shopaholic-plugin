<?php namespace Lovata\Shopaholic\Classes\Event;

use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Classes\Store\OfferListStore;
use Lovata\Shopaholic\Classes\Store\ProductListStore;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Settings;
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
     */
    public function subscribe()
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
            $this->clearProductActiveList();
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
        if($this->obElement->getOriginal('price') != $this->obElement->price) {
            $this->obOfferListStore->updateCacheBySorting(OfferListStore::SORT_PRICE_ASC);
            $this->obOfferListStore->updateCacheBySorting(OfferListStore::SORT_PRICE_DESC);
        }
        
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

        $this->clearProductActiveList();

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

    /**
     * Clear cached active product ID list
     */
    public function clearProductActiveList()
    {
        if(!Settings::getValue('check_offer_active')) {
            return;
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, ProductListStore::CACHE_TAG_LIST];
        $sCacheKey = ProductListStore::CACHE_TAG_LIST;

        //Clear cache data
        CCache::clear($arCacheTags, $sCacheKey);
        $this->obProductListStore->getActiveList();
    }

    /**
     * Get fields list for backend interface with switching visibility
     * @return array
     */
    public static function getConfiguredBackendFields() {
        return [
            'quantity'              => 'lovata.shopaholic::lang.field.quantity',
            'price'                 => 'lovata.shopaholic::lang.field.price',
            'old_price'             => 'lovata.shopaholic::lang.field.old_price',
            'code'                  => 'lovata.toolbox::lang.field.code',
            'external_id'           => 'lovata.toolbox::lang.field.external_id',
            'preview_text'          => 'lovata.toolbox::lang.field.preview_text',
            'description'           => 'lovata.toolbox::lang.field.description',
            'preview_image'         => 'lovata.toolbox::lang.field.preview_image',
            'images'                => 'lovata.toolbox::lang.field.images',
        ];
    }
}