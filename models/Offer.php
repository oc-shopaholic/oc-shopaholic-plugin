<?php namespace Lovata\Shopaholic\Models;

use Model;
use Event;

use Kharanenka\Helper\CustomValidationMessage;
use Kharanenka\Helper\DataFileModel;
use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Scope\SlugField;
use Kharanenka\Helper\CCache;

use Lovata\Shopaholic\Plugin;
use Lovata\Toolbox\Plugin as ToolboxPlugin;
use Lovata\Shopaholic\Classes\CPrice;
use Lovata\Shopaholic\Classes\ProductListStore;
use Lovata\Toolbox\Traits\Helpers\TraitClassExtension;

use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\SoftDelete;

/**
 * Class Offer
 * @package Lovata\Shopaholic\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * 
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 * @mixin \Lovata\CustomShopaholic\Classes\OfferExtend
 * 
 * @property $id
 * @property bool $active
 * @property string $name
 * @property string $slug
 * @property string $code
 * @property string $external_id
 * @property string $preview_text
 * @property string $description
 * @property double $price
 * @property double $old_price
 * @property integer $quantity
 * @property int $product_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * Relations
 * @property \System\Models\File $preview_image
 * @property \October\Rain\Database\Collection $images
 *
 * @property \Lovata\Shopaholic\Models\Product $product
 * @method $this|\October\Rain\Database\Relations\BelongsTo product()
 *
 * @method static $this getByProduct(int $iProductID)
 * @method static $this getByQuantity(int $iCount, string $sCondition = '=')
 * @method static $this getByPrice(int $iPrice, string $sCondition = '=')
 * @method static $this getByOldPrice(int $iPrice, string $sCondition = '=')
 */
class Offer extends Model
{
    use Validation;
    use SoftDelete;
    use ActiveField;
    use NameField;
    use SlugField;
    use CodeField;
    use ExternalIDField;
    use CustomValidationMessage;
    use DataFileModel;
    use TraitClassExtension;

    const CACHE_TAG_ELEMENT = 'shopaholic-offer-element';
    const CACHE_TAG_LIST = 'shopaholic-offer-list';

    public $table = 'lovata_shopaholic_offers';

    public $rules = ['name' => 'required'];
    public $customMessages = [];
    public $attributeNames = [];
    
    public $attachOne = ['preview_image' => 'System\Models\File'];
    public $attachMany = ['images' => 'System\Models\File'];
    public $belongsTo = ['product' => ['Lovata\Shopaholic\Models\Product']];

    public $fillable = [
        'name',
        'product_id',
        'active',
        'code',
        'external_id',
        'price',
        'old_price',
        'quantity',
        'preview_text',
    ];

    public $dates = ['created_at', 'updated_at', 'deleted_at'];
    public $appends = ['category'];
    public $casts = [];

    /**
     * Offer constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $iPreviewTextMaxLength = (int) Settings::getValue('offer_preview_limit_max');
        if($iPreviewTextMaxLength > 0) {
            $this->rules['preview_text'] = 'max:'.$iPreviewTextMaxLength;
        }

        $this->setCustomMessage(ToolboxPlugin::NAME, ['required', 'max.string']);
        $this->setCustomAttributeName(ToolboxPlugin::NAME, ['name', 'preview_text']);

        parent::__construct($attributes);
    }

    /**
     * After save method
     */
    public function afterSave()
    {
        $this->clearCache();

        ProductListStore::updateCacheAfterOfferSave($this);
        Event::fire(self::CACHE_TAG_ELEMENT.'.after.save', [$this]);
    }

    /**
     * After delete method
     */
    public function afterDelete()
    {
        $this->clearCache();

        ProductListStore::updateCacheAfterOfferDelete($this);
        Event::fire(self::CACHE_TAG_ELEMENT.'.after.delete', [$this]);
    }

    /**
     * Clear cache
     */
    public function clearCache()
    {
        //Clear product cache
        $iOriginalProductID = $this->getOriginal('product_id');
        $iProductID = $this->getAttribute('product_id');
        if($iOriginalProductID != $iProductID) {

            if(!empty($iOriginalProductID)) {
                CCache::clear([Plugin::CACHE_TAG, Product::CACHE_TAG_ELEMENT], $iOriginalProductID);
            }
            
            if(!empty($iProductID)) {
                CCache::clear([Plugin::CACHE_TAG, Product::CACHE_TAG_ELEMENT], $iProductID);
            }
        }

        //Clear offer data
        CCache::clear([Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT], $this->id);
        Event::fire(self::CACHE_TAG_ELEMENT.'.cache.clear', [$this]);
    }

    /**
     * Set addition property values
     * @param $arProperties
     */
    public function setPropertyAttribute($arProperties)
    {
        //TODO: Перенести в плагин
        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            \Lovata\PropertiesShopaholic\Classes\CProperty::setOfferPropertiesValues($arProperties, $this);
        }
    }

    //TODO: Удалить методы и проверить новую фишку с полями _category в клнфиге
    public function setCategoryAttribute($sValue) {}
    public function getCategoryAttribute() {}

    /**
     * Get offer data
     * @return  array
     */
    public function getData() {

        $arResult = [
            'id'                => $this->id,
            'name'              => $this->name,
            'code'              => $this->code,
            'product_id'        => $this->product_id,
            'preview_text'      => $this->preview_text,
            'preview_image'     => $this->getFileData('preview_image'),
            'description'       => $this->description,
            'images'            => $this->getFileListData('images'),
            'price'             => $this->price,
            'old_price'         => $this->old_price,
            'price_value'       => $this->getPriceValue(),
            'old_price_value'   => $this->getOldPriceValue(),
            'quantity'          => $this->quantity,
        ];

        self::extendMethodResult(__FUNCTION__, $arResult, [$this]);
        return $arResult;
    }

    /**
     * Get cached product data
     * @param int $iElementID
     * @param Offer $obElement
     * @param \October\Rain\Database\Collection $obSettings
     *      check_offer_active      - true|false
     *      check_offer_trashed     - true|false
     *
     * @return array|null
     */
    public static function getCacheData($iElementID, $obElement = null, $obSettings = null)
    {
        if(empty($iElementID)) {
            return null;
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT];
        $sCacheKey = $iElementID;

        $arResult = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arResult)) {
            
            //Get offer object
            if(empty($obElement)) {
                $obElement = self::withTrashed()->find($iElementID);
            }

            if(empty($obElement)) {
                return null;
            }

            $arResult = $obElement->getData();

            //Set cache data
            CCache::forever($arCacheTags, $sCacheKey, $arResult);
        }

        if(!self::checkOfferData($arResult, $obSettings)) {
            return null;
        }

        self::extendMethodResult(__FUNCTION__, $arResult);
        return $arResult;
    }

    /**
     * Check offer data
     * @param array $arResult
     * @param \October\Rain\Database\Collection $obSettings
     * @return bool
     */
    protected static function checkOfferData($arResult, $obSettings)
    {
        if(empty($obSettings)) {
            return $arResult['active'] && !$arResult['trashed'];
        }

        if($obSettings->get('check_offer_active') && !$arResult['active']) {
            return false;
        }

        if($obSettings->get('check_offer_trashed') && $arResult['trashed']) {
            return false;
        }

        return true;
    }

    /**
     * Get price value
     * @return double
     */
    public function getPriceValue()
    {
        return $this->attributes['price'];
    }

    /**
     * Get price value
     * @return double
     */
    public function getOldPriceValue()
    {
        return $this->attributes['old_price'];
    }
    
    /**
     * Accessor for price custom format
     *
     * @param  string  $dPrice
     * @return string
     */
    public function getPriceAttribute($dPrice)
    {
        return CPrice::get($dPrice);
    }

    /**
     * Accessor for discount price custom format
     *
     * @param  string  $dPrice
     * @return string
     */
    public function getOldPriceAttribute($dPrice)
    {
        return CPrice::get($dPrice);
    }

    /**
     * Format price to decimal format
     * @param  string  $sPrice
     */
    public function setPriceAttribute($sPrice)
    {
        $sPrice = str_replace(',', '.', $sPrice);
        $sPrice = preg_replace("/[^0-9\.]/", "",$sPrice);
        $this->attributes['price'] = $sPrice;
    }

    /**
     * Format discount price to decimal format
     * @param  string  $sPrice
     */
    public function setOldPriceAttribute($sPrice)
    {
        $sPrice = str_replace(',', '.', $sPrice);
        $sPrice = preg_replace("/[^0-9\.]/", "",$sPrice);
        if($sPrice <= $this->getPriceValue()) {
            $sPrice = 0;
        }
        
        $this->attributes['old_price'] = $sPrice;
    }

    /**
     * Get element by product ID
     * @param Offer $obQuery
     * @param $sData
     * @return Offer
     */
    public function scopeGetByProduct($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->where('product_id', $sData);
        }
        return $obQuery;
    }

    /**
     * Get by quantity
     * @param Offer $obQuery
     * @param $sData
     * @param string $sCondition
     * @return Offer
     */
    public function scopeGetByQuantity($obQuery, $sData, $sCondition = '=')
    {
        if(empty($sData)) {
            $sData = 0;
        }

        if(!empty($sCondition)) {
            $obQuery->where('quantity', $sCondition, $sData);
        }

        return $obQuery;
    }

    /**
     * Get by price
     * @param Offer $obQuery
     * @param $sData
     * @param string $sCondition
     * @return Offer
     */
    public function scopeGetByPrice($obQuery, $sData, $sCondition = '=')
    {
        if(empty($sData)) {
            $sData = 0;
        }

        if(!empty($sCondition)) {
            $obQuery->where('price', $sCondition, $sData);
        }

        return $obQuery;
    }

    /**
     * Get by old price
     * @param Offer $obQuery
     * @param $sData
     * @param string $sCondition
     * @return Offer
     */
    public function scopeGetByOldPrice($obQuery, $sData, $sCondition = '=')
    {
        if(empty($sData)) {
            $sData = 0;
        }

        if(!empty($sCondition)) {
            $obQuery->where('old_price', $sCondition, $sData);
        }

        return $obQuery;
    }
}