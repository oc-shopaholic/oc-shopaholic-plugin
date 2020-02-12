<?php namespace Lovata\Shopaholic\Classes\Helper;

use October\Rain\Support\Traits\Singleton;

use Lovata\Shopaholic\Classes\Item\MeasureItem;
use Lovata\Shopaholic\Models\Settings;

/**
 * Class MeasureHelper
 * @package Lovata\Shopaholic\Classes\Helper
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class MeasureHelper
{
    use Singleton;

    /**
     * Get dimensions unit measure
     * @return MeasureItem
     */
    public function getDimensionsMeasureItem()
    {
        $iMeasureID = Settings::getValue('dimensions_measure');
        $obMeasureItem = MeasureItem::make($iMeasureID);

        return $obMeasureItem;
    }

    /**
     * Get weight unit measure
     * @return MeasureItem
     */
    public function getWeightMeasureItem()
    {
        $iMeasureID = Settings::getValue('weight_measure');
        $obMeasureItem = MeasureItem::make($iMeasureID);

        return $obMeasureItem;
    }
}
