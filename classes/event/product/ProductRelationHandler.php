<?php namespace Lovata\Shopaholic\Classes\Event\Product;

use Lovata\Toolbox\Classes\Event\AbstractModelRelationHandler;

use Lovata\Shopaholic\Models\Product;
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
        $this->clearProductList($arAttachedIDList);
    }

    /**
     * After detach event handler
     * @param \Model $obModel
     * @param array  $arAttachedIDList
     */
    protected function afterDetach($obModel, $arAttachedIDList)
    {
        $this->clearProductList($arAttachedIDList);
    }

    /**
     * Clear cached product list
     * @param array $arAttachedIDList
     */
    protected function clearProductList($arAttachedIDList)
    {
        foreach ($arAttachedIDList as $iPromoBlockID) {
            ProductListStore::instance()->promo_block->clear($iPromoBlockID);
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
        return ['promo_block'];
    }
}
