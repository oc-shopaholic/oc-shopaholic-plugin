<?php namespace Lovata\Shopaholic\Models;

use Lovata\Shopaholic\Plugin;
use Kharanenka\Helper\CCache;
use October\Rain\Database\Model;

/**
 * Class Settings
 * @package Lovata\Shopaholic\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * @author Denis Plisko, d.plisko@lovata.com, LOVATA Group
 * 
 * @see Lovata\FilterShopaholic\Plugin::boot() - Extend columns (filter config)
 */
class Settings extends Model {
    
    protected static $arCacheTime = [];
    const CACHE_TAG = 'shopaholic-settings';
    const CACHE_TIME = 10080;
    
    public $implement = ['System.Behaviors.SettingsModel'];
    public $settingsCode = 'lovata_shopaholic_settings';
    public $settingsFields = 'fields.yaml';

    /**
     * Get setting value from cache
     * @param string $sCode
     * @return null|string
     */
    public static function getValue($sCode) {
        
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
        CCache::put($arTags, $sCode, $sResult, self::CACHE_TIME);

        return $sResult;
    }
    
    public function afterSave() {
        
        //Clear cache data
        $arValue = $this->value;
        foreach($arValue as $sKey => $sValue) {
            CCache::clear([Plugin::CACHE_TAG, self::CACHE_TAG], $sKey);
        }
    }

    /**
     * Get cache time
     * @param string $sKey
     * @return int|mixed
     */
    public static function getCacheTime($sKey) {

        if(empty($sKey)) {
            return Plugin::CACHE_TIME_DEFAULT;
        }

        if(isset(self::$arCacheTime[$sKey])) {
            return self::$arCacheTime[$sKey];
        }

        //Set cache data
        $iCacheTime = Settings::getValue($sKey);
        if(empty($iCacheTime) || $iCacheTime < 1) {
            $iCacheTime = Plugin::CACHE_TIME_DEFAULT;
        }

        self::$arCacheTime[$sKey] = $iCacheTime;
        return self::$arCacheTime[$sKey];
    }
}