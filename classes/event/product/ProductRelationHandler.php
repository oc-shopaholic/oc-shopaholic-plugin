<?php namespace Lovata\Shopaholic\Classes\Event\Product;

use Lovata\Toolbox\Classes\Event\AbstractModelRelationHandler;

use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Store\BrandListStore;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Store\ProductListStore;

/**
 * Class ProductRelationHandler
 * @package Lovata\Shopaholic\Classes\Event\Product
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductRelationHandler extends AbstractModelRelationHandler
{
    protected $iPriority = 900;

    /**
     * After attach event handler
     * @param \Model $obModel
     * @param array  $arAttachedIDList
     * @param array  $arInsertData
     */
    protected function afterAttach($obModel, $arAttachedIDList, $arInsertData)
    {
        $this->clearListByPromoBlock($arAttachedIDList);
        $this->clearListByCategory($arAttachedIDList);
    }

    /**
     * After detach event handler
     * @param \Model $obModel
     * @param array  $arAttachedIDList
     */
    protected function afterDetach($obModel, $arAttachedIDList)
    {
        $this->clearListByPromoBlock($arAttachedIDList);
        $this->clearListByCategory($arAttachedIDList);
    }

    /**
     * Clear cached product list by promo block ID
     * @param array $arAttachedIDList
     */
    protected function clearListByPromoBlock($arAttachedIDList)
    {
        if ($this->sRelationName != 'promo_block') {
            return;
        }

        foreach ($arAttachedIDList as $iPromoBlockID) {
            ProductListStore::instance()->promo_block->clear($iPromoBlockID);
        }
    }

    /**
     * Clear cached product list by category ID
     * @param array $arAttachedIDList
     */
    protected function clearListByCategory($arAttachedIDList)
    {
        if ($this->sRelationName != 'additional_category') {
            return;
        }

        foreach ($arAttachedIDList as $iCategoryID) {
            BrandListStore::instance()->category->clear($iCategoryID);
            ProductListStore::instance()->category->clear($iCategoryID);

            $obCategoryItem = CategoryItem::make($iCategoryID);
            if ($obCategoryItem->isNotEmpty()) {
                $obCategoryItem->clearProductCount();
            }
        }
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass() : string
    {
        return Product::class;
    }

    /**
     * Get relation name
     * @return array
     */
    protected function getRelationName()
    {
        return ['promo_block', 'additional_category'];
    }
}
