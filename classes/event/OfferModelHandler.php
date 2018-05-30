<?php namespace Lovata\Shopaholic\Classes\Event;

use Lovata\Toolbox\Classes\Event\ModelHandler;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Settings;
use Lovata\Shopaholic\Classes\Item\{
    OfferItem,
    ProductItem,
    CategoryItem
};

use Lovata\Shopaholic\Classes\Store\{
    OfferListStore,
    ProductListStore
};

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
     * After save event handler
     */
    protected function afterSave()
    {
        parent::afterSave();

        $this->checkProductIDField();
        $this->checkPriceField();

        $this->checkActiveField();
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        parent::afterDelete();

        if ($this->obElement->active) {
            $this->clearProductActiveList();
            $this->clearProductItemCache($this->obElement->product_id);
            $this->obListStore->active->clear();
        }

        //Get product object
        $obProduct = $this->obElement->product;
        if (empty($obProduct) || !$this->obElement->active) {
            return;
        }

        //Clear sorting product list by offer price
        $this->obProductListStore->sorting->clear(ProductListStore::SORT_PRICE_ASC);
        $this->obProductListStore->sorting->clear(ProductListStore::SORT_PRICE_DESC);
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
        $iProductID = $this->obElement->getAttribute('product_id');

        if ($iOriginalProductID == $iProductID) {
            return;
        }

        if (!empty($iOriginalProductID)) {
            $this->clearProductItemCache($iOriginalProductID);
        }

        if (!empty($iProductID)) {
            $this->clearProductItemCache($iProductID);
        }
    }

    /**
     * Clear cache, if price field changed
     */
    protected function checkPriceField()
    {
        if ($this->obElement->getOriginal('price') != $this->obElement->price_value) {
            $this->obListStore->sorting->clear(OfferListStore::SORT_PRICE_ASC);
            $this->obListStore->sorting->clear(OfferListStore::SORT_PRICE_DESC);
        }

        $bNeedUpdateFlag =
            $this->obElement->getOriginal('active') != $this->obElement->active
            || (
                $this->obElement->active
                && $this->obElement->getOriginal('active') == $this->obElement->active
                && $this->obElement->getOriginal('price') != $this->obElement->price_value
            );

        if (!$bNeedUpdateFlag) {
            return;
        }

        //Get product object
        $obProduct = $this->obElement->product;
        if (empty($obProduct)) {
            return;
        }

        $this->obProductListStore->sorting->clear(ProductListStore::SORT_PRICE_ASC);
        $this->obProductListStore->sorting->clear(ProductListStore::SORT_PRICE_DESC);
    }

    /**
     * Check offer "active" field, if it was changed, then clear cache
     */
    protected function checkActiveField()
    {
        //check offer "active" field
        if ($this->obElement->getOriginal('active') == $this->obElement->active) {
            return;
        }

        $this->clearProductActiveList();
        $this->obListStore->active->clear();

        $obProduct = $this->obElement->product;
        if (empty($obProduct)) {
            return;
        }

        $this->clearProductItemCache($this->obElement->product_id);

        $obCategoryItem = CategoryItem::make($obProduct->category_id);
        if ($obCategoryItem->isEmpty()) {
            return;
        }

        $obCategoryItem->clearProductCount();
    }

    /**
     * Clear cached active product ID list
     */
    protected function clearProductActiveList()
    {
        if (!Settings::getValue('check_offer_active')) {
            return;
        }

        $this->obProductListStore->active->clear();
    }

    /**
     * Init store objects
     */
    public function init()
    {
        $this->obProductListStore = ProductListStore::instance();
        $this->obListStore = OfferListStore::instance();
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
}
