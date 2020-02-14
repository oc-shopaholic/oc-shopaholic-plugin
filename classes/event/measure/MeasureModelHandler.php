<?php namespace Lovata\Shopaholic\Classes\Event\Measure;

use Lovata\Toolbox\Classes\Event\ModelHandler;

use Lovata\Shopaholic\Models\Measure;
use Lovata\Shopaholic\Classes\Item\MeasureItem;

/**
 * Class MeasureModelHandler
 * @package Lovata\Shopaholic\Classes\Event\Measure
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class MeasureModelHandler extends ModelHandler
{
    /** @var Measure */
    protected $obElement;
    
    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return Measure::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return MeasureItem::class;
    }
}
