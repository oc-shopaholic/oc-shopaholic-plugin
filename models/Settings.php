<?php namespace Lovata\Shopaholic\Models;

use Lovata\Toolbox\Models\CommonSettings;

/**
 * Class Settings
 * @package Lovata\Shopaholic\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 * @mixin \System\Behaviors\SettingsModel
 */
class Settings extends CommonSettings
{
    const SETTINGS_CODE = 'lovata_shopaholic_settings';

    public $settingsCode = 'lovata_shopaholic_settings';

    /**
     * Get measure list
     * @return array
     */
    public function getDimensionsMeasureOptions()
    {
        $arResult = (array) Measure::orderBy('name', 'asc')->lists('name', 'id');

        return $arResult;
    }

    /**
     * Get measure list
     * @return array
     */
    public function getWeightMeasureOptions()
    {
        $arResult = (array) Measure::orderBy('name', 'asc')->lists('name', 'id');

        return $arResult;
    }

    /**
     * Get measure list
     * @return array
     */
    public function getMeasureOfUnitOptions()
    {
        $arResult = (array) Measure::orderBy('name', 'asc')->lists('name', 'id');

        return $arResult;
    }
}
