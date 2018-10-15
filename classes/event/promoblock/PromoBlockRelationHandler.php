<?php namespace Lovata\Shopaholic\Classes\Event\PromoBlock;

use Lovata\Toolbox\Classes\Event\AbstractModelRelationHandler;

use Lovata\Shopaholic\Models\PromoBlock;
use Lovata\Shopaholic\Classes\Store\ProductListStore;

/**
 * Class PromoBlockRelationHandler
 * @package Lovata\Shopaholic\Classes\Event\PromoBlock
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class PromoBlockRelationHandler extends AbstractModelRelationHandler
{
    protected $iPriority = 900;

    /**
     * After attach event handler
     * @param PromoBlock $obModel
     * @param array    $arAttachedIDList
     * @param array    $arInsertData
     */
    protected function afterAttach($obModel, $arAttachedIDList, $arInsertData)
    {
        $this->clearCachedList($obModel);
    }

    /**
     * After detach event handler
     * @param PromoBlock $obModel
     * @param array    $arAttachedIDList
     */
    protected function afterDetach($obModel, $arAttachedIDList)
    {
        $this->clearCachedList($obModel);
    }

    /**
     * Clear cached list
     * @param PromoBlock $obModel
     */
    protected function clearCachedList($obModel)
    {
        ProductListStore::instance()->promo_block->clear($obModel->id);
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass() : string
    {
        return PromoBlock::class;
    }

    /**
     * Get relation name
     * @return array
     */
    protected function getRelationName()
    {
        return ['product'];
    }
}
