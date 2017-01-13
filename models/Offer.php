<?php namespace Lovata\Shopaholic\Models;

use Carbon\Carbon;
use Kharanenka\Helper\CustomValidationMessage;
use Kharanenka\Helper\DataFileModel;
use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Scope\SlugField;
use Lovata\Shopaholic\Classes\CPrice;
use Lovata\Shopaholic\Classes\ProductListStore;
use \October\Rain\Database\Traits\Validation;
use Model;

use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Plugin;
use Lovata\Toolbox\Plugin as ToolboxPlugin;
use October\Rain\Database\Builder;
use October\Rain\Database\Collection;
use October\Rain\Database\Traits\SoftDelete;
use System\Classes\PluginManager;
use Event;

/**
 * Class Offer
 * @package Lovata\Shopaholic\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * 
 * @mixin Builder
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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property \System\Models\File $preview_image
 * @property Collection $images
 * @property \Lovata\Shopaholic\Models\Product $product
 *
 * @method static $this hasActiveProduct()
 * @method static $this getByCategory(int $iCategoryID)
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

    const CACHE_TAG_ELEMENT = 'shopaholic-offer-element';
    const CACHE_TAG_LIST = 'shopaholic-offer-list';

    public $table = 'lovata_shopaholic_offers';
    
    //Validation
    public $rules = ['name' => 'required'];
    public $customMessages = [];
    public $attributeNames = [];
    
    public $attachOne = [
        'preview_image' => 'System\Models\File'
    ];

    public $attachMany = [
        'images' => 'System\Models\File'
    ];

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

    public $belongsTo = [
        'product' => ['Lovata\Shopaholic\Models\Product'],
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

        if(PluginManager::instance()->hasPlugin('Lovata.CustomShopaholic')) {
            \Lovata\CustomShopaholic\Classes\OfferExtend::constructExtend($this);
        }
        
        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            \Lovata\PropertiesShopaholic\Classes\OfferExtend::constructExtend($this);
        }

        parent::__construct($attributes);
    }

    public function afterSave()
    {
        $this->clearCache();
        ProductListStore::updateCacheAfterOfferSave($this);
        Event::fire(self::CACHE_TAG_ELEMENT.'.after.save', [$this]);
    }

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
     * Get addition property values
     * @return array
     */
    public function getPropertyAttribute()
    {
        $arResult = [];
        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            $arResult = \Lovata\PropertiesShopaholic\Classes\CProperty::getOfferPropertiesValues($this);
        }

        return $arResult;
    }

    /**
     * Set addition property values
     * @param $arProperties
     */
    public function setPropertyAttribute($arProperties)
    {
        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            \Lovata\PropertiesShopaholic\Classes\CProperty::setOfferPropertiesValues($arProperties, $this);
        }
    }

    //TODO: Удалить методы и проверить новую фишку с полями _category в клнфиге
    public function setCategoryAttribute($sValue) {}
    public function getCategoryAttribute() {}

    /**
     * Has active product
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasActiveProduct($obQuery)
    {
        $obQuery->whereHas('product', function($obQuery) {
            /** @var Product $obQuery */
            $obQuery->active();
        });

        return $obQuery;
    }
    
    /**
     * Get by category ID
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @param $sData
     * @return mixed
     */
    public function scopeGetByCategory($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->whereHas('product', function($obQuery) use ($sData) {
                /** @var Product $obQuery */
                $obQuery->active()->getByCategory($sData);
            });
        }

        return $obQuery;
    }

    /**
     * Get element by product ID
     * @param \October\Rain\Database\QueryBuilder $obQuery
     * @param $sData
     * @return \October\Rain\Database\QueryBuilder
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
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @param $sData
     * @param string $sCondition
     * @return mixed
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
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @param $sData
     * @param string $sCondition
     * @return mixed
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
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @param $sData
     * @param string $sCondition
     * @return mixed
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

    /**
     * Get offer data
     * @return  array|void
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

        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            $arResult['property'] = \Lovata\PropertiesShopaholic\Classes\CProperty::getOfferPropertiesList($this);
        }

        if(PluginManager::instance()->hasPlugin('Lovata.CustomShopaholic')) {
            \Lovata\CustomShopaholic\Classes\OfferExtend::getData($arResult, $this);
        }

        return $arResult;
    }

    /**
     * Get cached product data
     * @param int $iElementID
     * @param Offer $obElement
     * @param bool $bCheckActive
     * @return array|null|string|void
     */
    public static function getCacheData($iElementID, $obElement = null, $bCheckActive = true)
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
                if($bCheckActive) {
                    $obElement = self::active()->find($iElementID);
                } else {
                    $obElement = self::find($iElementID);
                }
            }

            if(empty($obElement)) {
                return null;
            }

            $arResult = $obElement->getData();

            //Set cache data
            CCache::forever($arCacheTags, $sCacheKey, $arResult);
        }

        if(empty($arResult)) {
            return null;
        }

        if(PluginManager::instance()->hasPlugin('Lovata.CustomShopaholic')) {
            \Lovata\CustomShopaholic\Classes\OfferExtend::getCacheData($arResult);
        }
        
        return $arResult;
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
        return CPrice::getPriceInFormat($dPrice);
    }

    /**
     * Accessor for discount price custom format
     *
     * @param  string  $dPrice
     * @return string
     */
    public function getOldPriceAttribute($dPrice)
    {
        return CPrice::getPriceInFormat($dPrice);
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
     * Get fields list for backend interface with switching visibility
     * @return array
     */
    public static function getConfiguredBackendFields() {
        return [
            'quantity'              => 'lovata.shopaholic::lang.field.quantity',
            'price'                 => 'lovata.shopaholic::lang.field.price',
            'old_price'             => 'lovata.shopaholic::lang.field.old_price',
            'code'                  => 'lovata.toolbox::lang.field.code',
            'external_id'           => 'lovata.toolbox::lang.field.external_id',
            'preview_text'          => 'lovata.toolbox::lang.field.preview_text',
            'description'           => 'lovata.toolbox::lang.field.description',
            'preview_image'         => 'lovata.toolbox::lang.field.preview_image',
            'images'                => 'lovata.toolbox::lang.field.images',
        ];
    }
}