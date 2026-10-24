<?php namespace Lovata\Shopaholic\Classes\Event\Offer;

use Lovata\Toolbox\Classes\Event\AbstractImageSetsFieldHandler;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Controllers\Offers;

/**
 * Class ExtendOfferImageSetsFieldHandler
 * Adds image sets field to offer form
 * @package Lovata\Shopaholic\Classes\Event\Offer
 * @author  Pavel Lautsevich, p.lautsevich@lovata.com, LOVATA Group
 */
class ExtendOfferImageSetsFieldHandler extends AbstractImageSetsFieldHandler
{
    /**
     * Get tab name for image sets field
     * @return string
     */
    protected function getTabName() : string
    {
        return 'lovata.toolbox::lang.tab.images';
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass() : string
    {
        return Offer::class;
    }

    /**
     * Get controller class name
     * @return string
     */
    protected function getControllerClass() : string
    {
        return Offers::class;
    }
}
