<?php namespace Lovata\Shopaholic\Classes\Event;

use Lovata\Toolbox\Classes\Event\ModelHandler;

use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Store\BrandListStore;
use Lovata\Shopaholic\Classes\Store\ProductListStore;

/**
 * Class ProductModelHandler
 * @package Lovata\Shopaholic\Classes\Event
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductModelHandler extends ModelHandler
{
    /** @var  Product */
    protected $obElement;

    /** @var  ProductListStore */
    protected $obListStore;

    /** @var  BrandListStore */
    protected $obBrandListStore;

    /**
     * ProductModelHandler constructor.
     */
    public function __construct()
    {
        $this->obListStore = ProductListStore::instance();
        $this->obBrandListStore = BrandListStore::instance();
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

    /**
     * After create event handler
     */
    protected function afterCreate()
    {
        parent::afterCreate();

        $this->obListStore->sorting->clear(ProductListStore::SORT_NEW);
        $this->obListStore->sorting->clear(ProductListStore::SORT_NO);
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

        $this->checkFieldChanges('active', $this->obListStore->active);
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        $this->processOfferAfterDelete();
        parent::afterDelete();

        $this->obListStore->category->clear($this->obElement->category_id);
        $this->obBrandListStore->category->clear($this->obElement->category_id);

        $this->obListStore->brand->clear($this->obElement->brand_id);

        $this->obListStore->sorting->clear(ProductListStore::SORT_PRICE_ASC);
        $this->obListStore->sorting->clear(ProductListStore::SORT_PRICE_DESC);
        $this->obListStore->sorting->clear(ProductListStore::SORT_NEW);
        $this->obListStore->sorting->clear(ProductListStore::SORT_NO);

        if ($this->obElement->active) {
            $this->obListStore->active->clear();
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
        if ($this->obElement->getOriginal('active') == $this->obElement->active) {
            return;
        }

        $this->obListStore->active->clear();

        $obCategoryItem = CategoryItem::make($this->obElement->category_id);
        if ($obCategoryItem->isEmpty()) {
            return;
        }

        $obCategoryItem->clearProductCount();
    }

    /**
     * Check product "category_id" field, if it was changed, then clear cache
     */
    private function checkCategoryIDField()
    {
        //Check "category_id" field
        if ($this->obElement->getOriginal('category_id') == $this->obElement->category_id) {
            return;
        }

        //Update product ID cache list for category
        $this->obListStore->category->clear($this->obElement->category_id);
        $this->obListStore->category->clear((int) $this->obElement->getOriginal('category_id'));

        $this->obBrandListStore->category->clear($this->obElement->category_id);
        $this->obBrandListStore->category->clear((int) $this->obElement->getOriginal('category_id'));
    }

    /**
     * Check product "brand_id" field, if it was changed, then clear cache
     */
    private function checkBrandIDField()
    {
        //Check "brand_id" field
        if ($this->obElement->getOriginal('brand_id') == $this->obElement->brand_id) {
            return;
        }

        //Update product ID cache list for brand
        $this->obListStore->brand->clear($this->obElement->brand_id);
        $this->obListStore->brand->clear((int) $this->obElement->getOriginal('brand_id'));
    }
}
