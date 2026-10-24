<?php namespace Lovata\Shopaholic\Classes\Event\Product;

use Lovata\Toolbox\Classes\Event\AbstractImageSetsFieldHandler;

use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Controllers\Products;

/**
 * Class ExtendProductImageSetsFieldHandler
 * Adds image sets field to product form
 * @package Lovata\Shopaholic\Classes\Event\Product
 * @author  Pavel Lautsevich, p.lautsevich@lovata.com, LOVATA Group
 */
class ExtendProductImageSetsFieldHandler extends AbstractImageSetsFieldHandler
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
        return Product::class;
    }

    /**
     * Get controller class name
     * @return string
     */
    protected function getControllerClass() : string
    {
        return Products::class;
    }
}
