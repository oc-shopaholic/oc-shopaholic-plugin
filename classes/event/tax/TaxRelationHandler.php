<?php namespace Lovata\Shopaholic\Classes\Event\Tax;

use Lovata\Shopaholic\Classes\Item\TaxItem;
use Lovata\Toolbox\Classes\Event\AbstractModelRelationHandler;

use Lovata\Shopaholic\Models\Tax;

/**
 * Class TaxRelationHandler
 * @package Lovata\Shopaholic\Classes\Event\Tax
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class TaxRelationHandler extends AbstractModelRelationHandler
{
    protected $iPriority = 900;

    /**
     * After attach event handler
     * @param Tax $obModel
     * @param array    $arAttachedIDList
     * @param array    $arInsertData
     */
    protected function afterAttach($obModel, $arAttachedIDList, $arInsertData)
    {
        TaxItem::clearCache($obModel->id);
    }

    /**
     * After detach event handler
     * @param Tax $obModel
     * @param array    $arAttachedIDList
     */
    protected function afterDetach($obModel, $arAttachedIDList)
    {
        TaxItem::clearCache($obModel->id);
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass() : string
    {
        return Tax::class;
    }

    /**
     * Get relation name
     * @return array
     */
    protected function getRelationName()
    {
        return ['category', 'product', 'country', 'state'];
    }
}
