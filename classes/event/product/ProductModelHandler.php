<?php namespace Lovata\Shopaholic\Classes\Event\Product;

use Lovata\Toolbox\Models\Settings;
use Lovata\Toolbox\Classes\Event\ModelHandler;

use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\PriceType;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Store\BrandListStore;
use Lovata\Shopaholic\Classes\Store\ProductListStore;

/**
 * Class ProductModelHandler
 * @package Lovata\Shopaholic\Classes\Event\Product
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductModelHandler extends ModelHandler
{
    /** @var  Product */
    protected $obElement;
    protected $bWithRestore = true;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        parent::subscribe($obEvent);

        Product::extend(function ($obModel) {
            /** @var Product $obModel */
            $bSlugIsTranslatable = Settings::getValue('slug_is_translatable');
            if ($bSlugIsTranslatable) {
                $obModel->translatable[] = ['slug', 'index' => true];
            }
        });
    }

    /**
     * After create event handler
     */
    protected function afterCreate()
    {
        parent::afterCreate();

        ProductListStore::instance()->sorting->clear(ProductListStore::SORT_NEW);
        ProductListStore::instance()->sorting->clear(ProductListStore::SORT_NO);
    }

    /**
     * After save event handler
     */
    protected function afterSave()
    {
        parent::afterSave();

        //Check "category_id" field
        $this->checkCategoryIDField();

        //Check "brand_id" field
        $this->checkBrandIDField();

        $this->checkActiveField();
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        $this->processOfferAfterDelete();
        parent::afterDelete();

        ProductListStore::instance()->category->clear($this->obElement->category_id);
        BrandListStore::instance()->category->clear($this->obElement->category_id);
        $this->clearCategoryProductCount($this->obElement->category_id);

        ProductListStore::instance()->brand->clear($this->obElement->brand_id);

        $this->clearProductSortingByPrice();
        ProductListStore::instance()->sorting->clear(ProductListStore::SORT_NEW);
        ProductListStore::instance()->sorting->clear(ProductListStore::SORT_NO);

        if ($this->obElement->active) {
            ProductListStore::instance()->active->clear();
        }

        $arAdditionalCategoryIDList = $this->obElement->additional_category->lists('id');
        if (empty($arAdditionalCategoryIDList)) {
            return;
        }

        foreach ($arAdditionalCategoryIDList as $iCategoryID) {
            $this->clearCategoryProductCount($iCategoryID);
            ProductListStore::instance()->category->clear($iCategoryID);
        }
    }

    /**
     * After restore event handler
     */
    protected function afterRestore()
    {
        parent::afterRestore();

        ProductListStore::instance()->category->clear($this->obElement->category_id);
        BrandListStore::instance()->category->clear($this->obElement->category_id);
        $this->clearCategoryProductCount($this->obElement->category_id);

        ProductListStore::instance()->brand->clear($this->obElement->brand_id);

        $this->clearProductSortingByPrice();
        ProductListStore::instance()->sorting->clear(ProductListStore::SORT_NEW);
        ProductListStore::instance()->sorting->clear(ProductListStore::SORT_NO);

        if ($this->obElement->active) {
            ProductListStore::instance()->active->clear();
        }

        $arAdditionalCategoryIDList = $this->obElement->additional_category->lists('id');
        if (empty($arAdditionalCategoryIDList)) {
            return;
        }

        foreach ($arAdditionalCategoryIDList as $iCategoryID) {
            $this->clearCategoryProductCount($iCategoryID);
            ProductListStore::instance()->category->clear($iCategoryID);
        }
    }

    /**
     * Set active = false in offer list, after product was removed
     */
    protected function processOfferAfterDelete()
    {
        $obOfferList = $this->obElement->offer;
        if ($obOfferList->isEmpty()) {
            return;
        }

        foreach ($obOfferList as $obOffer) {
            $obOffer->active = false;
            $obOffer->save();
        }
    }

    /**
     * Check offer "active" field, if it was changed, then clear cache
     */
    protected function checkActiveField()
    {
        //check product "active" field
        if (!$this->isFieldChanged('active')) {
            return;
        }

        ProductListStore::instance()->active->clear();

        $this->clearCategoryProductCount($this->obElement->category_id);

        //Get additional category ID list
        $arAdditionalCategoryIDList = $this->obElement->additional_category->lists('id');
        if (empty($arAdditionalCategoryIDList)) {
            return;
        }

        foreach ($arAdditionalCategoryIDList as $iCategoryID) {
            $this->clearCategoryProductCount($iCategoryID);
        }
    }

    /**
     * Check product "category_id" field, if it was changed, then clear cache
     */
    protected function checkCategoryIDField()
    {
        //Check "category_id" field
        if (!$this->isFieldChanged('category_id')) {
            return;
        }

        //Update product ID cache list for category
        ProductListStore::instance()->category->clear($this->obElement->category_id);
        ProductListStore::instance()->category->clear((int) $this->obElement->getOriginal('category_id'));

        BrandListStore::instance()->category->clear($this->obElement->category_id);
        BrandListStore::instance()->category->clear((int) $this->obElement->getOriginal('category_id'));

        $this->clearCategoryProductCount($this->obElement->category_id);
        $this->clearCategoryProductCount((int) $this->obElement->getOriginal('category_id'));
    }

    /**
     * Check product "brand_id" field, if it was changed, then clear cache
     */
    protected function checkBrandIDField()
    {
        //Check "brand_id" field
        if ($this->obElement->getOriginal('brand_id') == $this->obElement->brand_id) {
            return;
        }

        //Update product ID cache list for brand
        ProductListStore::instance()->brand->clear($this->obElement->brand_id);
        ProductListStore::instance()->brand->clear((int) $this->obElement->getOriginal('brand_id'));
    }

    /**
     * Clear product count cache in category item
     * @param int $iCategoryID
     */
    protected function clearCategoryProductCount($iCategoryID)
    {
        $obCategoryItem = CategoryItem::make($iCategoryID);
        if ($obCategoryItem->isNotEmpty()) {
            $obCategoryItem->clearProductCount();
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
        return Product::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return ProductItem::class;
    }
}
