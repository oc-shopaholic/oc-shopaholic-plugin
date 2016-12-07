<?php namespace Lovata\Shopaholic\Models;

use Yaml;
use Lovata\Shopaholic\Plugin;
use Kharanenka\Helper\CCache;
use October\Rain\Database\Model;

/**
 * Class Settings
 * @package Lovata\Shopaholic\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Settings extends Model {

    const CACHE_TAG = 'shopaholic-settings';

    protected static $arFields = null;
    public $implement = ['System.Behaviors.SettingsModel'];
    public $settingsCode = 'lovata_shopaholic_settings';
    public $settingsFields = 'fields.yaml';

    public function __construct(array $attributes = [])
    {
        if(empty(self::$arFields)) {
            $this->initFieldsConfig();
        }

        $this->settingsFields = self::$arFields;
        parent::__construct($attributes);
    }

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
        CCache::forever($arTags, $sCode, $sResult);

        return $sResult;
    }
    
    public function afterSave() {
        
        //Clear cache data
        $arValue = $this->value;
        foreach($arValue as $sKey => $sValue) {
            CCache::clear([Plugin::CACHE_TAG, self::CACHE_TAG], $sKey);
        }
    }

    protected function initFieldsConfig() {

        $arConfig = Yaml::parseFile(__DIR__.'/settings/fields.yaml');
        if(empty($arConfig)) {
            return;
        }

        self::$arFields = $arConfig;

        $this->addConfiguredFields(Product::getConfiguredBackendFields(), 'product');
        $this->addConfiguredFields(Offer::getConfiguredBackendFields(), 'offer');
        $this->addConfiguredFields(Category::getConfiguredBackendFields(), 'category');
        $this->addConfiguredFields(Brand::getConfiguredBackendFields(), 'brand');
    }

    protected function addConfiguredFields($arFields, $sName) {

        if(empty($arFields) || empty($sName)) {
            return;
        }

        self::$arFields['tabs']['fields'][$sName.'_view_off'] = [
            'tab' => 'lovata.shopaholic::lang.tab.field_view',
            'label' => 'lovata.shopaholic::lang.settings.'.$sName,
            'span' => 'left',
            'type' => 'section',
        ];

        foreach($arFields as $sFieldName => $sFieldLabel) {

            if(empty($sFieldName)) {
                continue;
            }

            self::$arFields['tabs']['fields'][$sName.'_'.$sFieldName] = [
                'tab' => 'lovata.shopaholic::lang.tab.field_view',
                'label' => $sFieldLabel,
                'type' => 'checkbox',
                'span' => 'left',
            ];
        }
    }
}