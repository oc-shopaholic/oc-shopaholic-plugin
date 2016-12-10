<?php namespace Lovata\Shopaholic\Models;

use October\Rain\Database\Builder;
use Lovata\Shopaholic\Plugin;
use Kharanenka\Helper\CCache;
use October\Rain\Database\Model;

/**
 * Class Settings
 * @package Lovata\Shopaholic\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin Builder
 * @mixin \Eloquent
 * @mixin \System\Behaviors\SettingsModel
 */
class Settings extends Model
{
    const CACHE_TAG = 'shopaholic-settings';

    public $implement = ['System.Behaviors.SettingsModel'];
    public $settingsCode = 'lovata_shopaholic_settings';
    public $settingsFields = 'fields.yaml';

    protected static $arFields = [];

    /**
     * Get setting value from cache
     * @param string $sCode
     * @return null|string
     */
    public static function getValue($sCode)
    {
        if(empty($sCode)) {
            return '';
        }

        $arTags = [Plugin::CACHE_TAG, self::CACHE_TAG];
        
        //Get value from cache
        $sResult = CCache::get($arTags, $sCode);
        if(!empty($sResult)) {
            return $sResult;
        }

        //Get value
        $sResult = self::get($sCode);
        
        //Set cache data
        CCache::forever($arTags, $sCode, $sResult);

        return $sResult;
    }
    
    public function afterSave()
    {
        //Clear cache data
        $arValue = $this->value;
        foreach($arValue as $sKey => $sValue) {
            CCache::clear([Plugin::CACHE_TAG, self::CACHE_TAG], $sKey);
        }
    }

    /**
     * Init addition fields config
     * @return array
     */
    public static function getAdditionFields()
    {
        self::addConfiguredFields(Product::getConfiguredBackendFields(), 'product');
        self::addConfiguredFields(Offer::getConfiguredBackendFields(), 'offer');
        self::addConfiguredFields(Category::getConfiguredBackendFields(), 'category');
        self::addConfiguredFields(Brand::getConfiguredBackendFields(), 'brand');

        return self::$arFields;
    }

    /**
     * @param array $arFields
     * @param string $sName
     */
    protected static function addConfiguredFields($arFields, $sName)
    {
        if(empty($arFields) || empty($sName)) {
            return;
        }

        self::$arFields[$sName.'_view_off'] = [
            'tab'       => 'lovata.shopaholic::lang.tab.field_view',
            'label'     => 'lovata.shopaholic::lang.settings.'.$sName,
            'span'      => 'left',
            'type'      => 'section',
        ];

        foreach($arFields as $sFieldName => $sFieldLabel) {

            if(empty($sFieldName)) {
                continue;
            }

            self::$arFields[$sName.'_'.$sFieldName] = [
                'tab'       => 'lovata.shopaholic::lang.tab.field_view',
                'label'     => $sFieldLabel,
                'type'      => 'checkbox',
                'span'      => 'left',
            ];
        }
    }
}