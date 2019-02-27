<?php namespace Lovata\Shopaholic\Classes\Event\Offer;

use Lovata\Shopaholic\Models\PriceType;
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
 * @package Lovata\Shopaholic\Classes\Event\Offer
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class OfferModelHandler extends ModelHandler
{
    /** @var  Offer */
    protected $obElement;
    protected $bWithRestore = true;

    /**
     * After save event handler
     */
    protected function afterCreate()
    {
        OfferListStore::instance()->sorting->clear(OfferListStore::SORT_NO);
        OfferListStore::instance()->sorting->clear(OfferListStore::SORT_NEW);
    }

    /**
     * After save event handler
     */
    protected function afterSave()
    {
        parent::afterSave();

        $this->checkProductIDField();

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
            OfferListStore::instance()->active->clear();

            $this->clearOfferSortingByPrice();

            //Clear sorting product list by offer price
            $this->clearProductSortingByPrice();
        }

        OfferListStore::instance()->sorting->clear(OfferListStore::SORT_NO);
        OfferListStore::instance()->sorting->clear(OfferListStore::SORT_NEW);
    }

    /**
     * After restore event handler
     */
    protected function afterRestore()
    {
        parent::afterRestore();

        if ($this->obElement->active) {
            $this->clearProductActiveList();
            $this->clearProductItemCache($this->obElement->product_id);
            OfferListStore::instance()->active->clear();

            $this->clearOfferSortingByPrice();

            //Clear sorting product list by offer price
            $this->clearProductSortingByPrice();
        }

        OfferListStore::instance()->sorting->clear(OfferListStore::SORT_NO);
        OfferListStore::instance()->sorting->clear(OfferListStore::SORT_NEW);
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
     * Check offer "active" field, if it was changed, then clear cache
     */
    protected function checkActiveField()
    {
        //check offer "active" field
        if ($this->obElement->getOriginal('active') == $this->obElement->active) {
            return;
        }

        $this->clearProductActiveList();
        OfferListStore::instance()->active->clear();

        $this->clearProductSortingByPrice();

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

        ProductListStore::instance()->active->clear();
    }

    /**
     * Clear offer sorting cache by price
     */
    protected function clearOfferSortingByPrice()
    {
        OfferListStore::instance()->sorting->clear(OfferListStore::SORT_PRICE_ASC);
        OfferListStore::instance()->sorting->clear(OfferListStore::SORT_PRICE_DESC);

        //Get price types
        $obPriceTypeList = PriceType::active()->get();
        if ($obPriceTypeList->isEmpty()) {
            return;
        }

        foreach ($obPriceTypeList as $obPriceType) {
            OfferListStore::instance()->sorting->clear(OfferListStore::SORT_PRICE_ASC.'|'.$obPriceType->code);
            OfferListStore::instance()->sorting->clear(OfferListStore::SORT_PRICE_DESC.'|'.$obPriceType->code);
        }
    }

    /**
     * Clear offer sorting cache by price
     */
    protected function clearProductSortingByPrice()
    {
        ProductListStore::instance()->sorting->clear(ProductListStore::SORT_PRICE_ASC);
        ProductListStore::instance()->sorting->clear(ProductListStore::SORT_PRICE_DESC);

        //Get price types
        $obPriceTypeList = PriceType::active()->get();
        if ($obPriceTypeList->isEmpty()) {
            return;
        }

        foreach ($obPriceTypeList as $obPriceType) {
            ProductListStore::instance()->sorting->clear(ProductListStore::SORT_PRICE_ASC.'|'.$obPriceType->code);
            ProductListStore::instance()->sorting->clear(ProductListStore::SORT_PRICE_DESC.'|'.$obPriceType->code);
        }
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
