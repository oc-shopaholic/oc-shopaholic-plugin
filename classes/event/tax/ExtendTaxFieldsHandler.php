<?php namespace Lovata\Shopaholic\Classes\Event\Tax;

use System\Classes\PluginManager;
use Lovata\Toolbox\Classes\Event\AbstractBackendFieldHandler;

use Lovata\Shopaholic\Models\Tax;
use Lovata\Shopaholic\Controllers\Taxes;

/**
 * Class ExtendTaxFieldsHandler
 * @package Lovata\Shopaholic\Classes\Event\Tax
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ExtendTaxFieldsHandler extends AbstractBackendFieldHandler
{
    /**
     * Extend backend fields
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function extendFields($obWidget)
    {
        if (PluginManager::instance()->hasPlugin('RainLab.Location')) {
            return;
        }

        $obWidget->removeField('country');
        $obWidget->removeField('state');
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
     * Get controller class name
     * @return string
     */
    protected function getControllerClass() : string
    {
        return Taxes::class;
    }
}
