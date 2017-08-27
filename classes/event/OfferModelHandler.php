<?php namespace Lovata\Shopaholic\Classes\Event;

use Lovata\Toolbox\Classes\Event\ModelHandler;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Settings;
use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Store\OfferListStore;
use Lovata\Shopaholic\Classes\Store\ProductListStore;

/**
 * Class OfferModelHandler
 * @package Lovata\Shopaholic\Classes\Event
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class OfferModelHandler extends ModelHandler
{
    /** @var  Offer */
    protected $obElement;

    /** @var  ProductListStore */
    protected $obProductListStore;

    /** @var  OfferListStore */
    protected $obListStore;

    /**
     * OfferModelHandler constructor.
     *
     * @param ProductListStore $obProductListStore
     * @param OfferListStore   $obOfferListStore
     */
    public function __construct(ProductListStore $obProductListStore, OfferListStore $obOfferListStore)
    {
        $this->obProductListStore = $obProductListStore;
        $this->obListStore = $obOfferListStore;
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return Offer::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return OfferItem::class;
    }

    /**
     * After save event handler
     */
    protected function afterSave()
    {
        parent::afterSave();

        $this->checkProductIDField();
        $this->checkPriceField();
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        
        if($this->obElement->active) {
            $this->clearProductActiveList();
            $this->clearProductItemCache($this->obElement->product_id);
        }

        //Get product object
        $obProduct = $this->obElement->product;
        if(empty($obProduct) || !$this->obElement->active) {
            return;
        }

        //Clear sorting product list by offer price
        $this->obProductListStore->updateCacheBySorting(ProductListStore::SORT_PRICE_ASC);
        $this->obProductListStore->updateCacheBySorting(ProductListStore::SORT_PRICE_DESC);
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
            $this->obListStore->updateCacheBySorting(OfferListStore::SORT_PRICE_ASC);
            $this->obListStore->updateCacheBySorting(OfferListStore::SORT_PRICE_DESC);
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
    protected function checkActiveField()
    {
        //check product "active" field
        if($this->obElement->getOriginal('active') == $this->obElement->active) {
            return;
        }

        $this->clearProductActiveList();
        $this->obListStore->clearActiveList();

        $obProduct = $this->obElement->product;
        if(empty($obProduct)) {
            return;
        }

        $this->clearProductItemCache($this->obElement->product_id);
        
        $obCategoryItem = CategoryItem::make($obProduct->category_id);
        if($obCategoryItem->isEmpty()) {
            return;
        }

        $obCategoryItem->clearProductCount();
    }

    /**
     * Clear cached active product ID list
     */
    public function clearProductActiveList()
    {
        if(!Settings::getValue('check_offer_active')) {
            return;
        }

        $this->obProductListStore->clearActiveList();
    }
}