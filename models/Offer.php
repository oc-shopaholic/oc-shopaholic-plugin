<?php namespace Lovata\Shopaholic\Models;

use Carbon\Carbon;
use Lang;
use Lovata\Shopaholic\Classes\CPrice;
use Model;

use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Plugin;
use October\Rain\Database\Builder;
use October\Rain\Database\Collection;
use October\Rain\Database\QueryBuilder;
use October\Rain\Database\Traits\SoftDelete;
use System\Classes\PluginManager;
use Lovata\Shopaholic\Classes\CommonTrait;
use Event;

/**
 * Class Offer
 * @package Lovata\Shopaholic\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * @author Denis Plisko, d.plisko@lovata.com, LOVATA Group
 * 
 * @mixin Builder
 * @mixin \Eloquent
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
 * @method static $this deleted()
 * @method static $this withDeleted()
 * @method static $this hasActiveProduct()
 * @method static $this getByCategory(int $iCategoryID)
 * @method static $this getByProduct(int $iProductID)
 * @method static $this likeByCode(string $sCode)
 * @method static $this getByMinQuantity(int $iCount, bool $bFlag = false)
 * @method static $this getByMinPrice(int $iPrice, bool $bFlag = false)
 * @method static $this getByMaxPrice(int $iPrice, bool $bFlag = false)
 * @method static $this getByMinOldPrice(int $iPrice, bool $bFlag = false)
 */
class Offer extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use SoftDelete;
    use CommonTrait;

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

    protected $fillable = [
        'name',
        'product_id',
        'active',
        'code',
        'external_id',
        'price',
        'old_price',
        'quantity',
    ];

    public $belongsTo = [
        'product' => ['Lovata\Shopaholic\Models\Product'],
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    public $appends = ['category'];

    public function __construct(array $attributes = [])
    {
        //TODO: Перенести в пакет
        //Set validation custom messages
        $this->customMessages = [
            'required' => Lang::get('lovata.shopaholic::lang.validation.required'),
            'unique' => Lang::get('lovata.shopaholic::lang.validation.unique'),
        ];

        //Set validation custom attributes name
        $this->attributeNames = [
            'name' => Lang::get('lovata.shopaholic::lang.fields.name'),
            'slug' => Lang::get('lovata.shopaholic::lang.fields.slug'),
        ];

        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            $this->appends[] = 'property';
        }

        parent::__construct($attributes);
    }

    public function afterSave()
    {
        $this->clearCache();
        Event::fire(self::CACHE_TAG_ELEMENT.'.after.save', [$this]);
    }

    public function afterDelete()
    {
        $this->clearCache();
        Event::fire(self::CACHE_TAG_ELEMENT.'.after.delete', [$this]);
    }

    /**
     * Check active product
     */
    protected function checkActiveProduct() {

        //Get product ID
        $iProductID = $this->getOriginal('product_id');
        if(empty($iProductID)) {
            return;
        }
        
        //Get product
        /** @var Product $obProduct */
        $obProduct = Product::find($iProductID);
        $bActive = $obProduct->checkActiveOffers();
        
        if($obProduct->active && !$bActive) {
            $obProduct->active = false;
            $obProduct->save();
        }
    }

    public function scopeDeleted($query)
    {
        return $query->onlyTrashed();
    }

    public function scopeWithDeleted($query)
    {
        return $query->withTrashed();
    }

    /**
     * Get addition property values
     * @return array
     */
    public function getPropertyAttribute() {

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
    public function setPropertyAttribute($arProperties) {

        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            \Lovata\PropertiesShopaholic\Classes\CProperty::setOfferPropertiesValues($arProperties, $this);
        }
    }
    
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
     * Get by min count
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @param $sData
     * @param $bFlag
     * @return mixed
     */
    public function scopeGetByMinQuantity($obQuery, $sData, $bFlag = false)
    {
        if(empty($sData)) {
            $sData = 0;
        }

        if ($bFlag) {
            $obQuery->where('quantity', '>=', $sData);
        } else {
            $obQuery->where('quantity', '>', $sData);
        }

        return $obQuery;
    }

    /**
     * Get by min price
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @param $sData
     * @param $bFlag
     * @return mixed
     */
    public function scopeGetByMinPrice($obQuery, $sData, $bFlag = false)
    {
        if(empty($sData)) {
            $sData = 0;
        }

        if ($bFlag) {
            $obQuery->where('price', '>=', $sData);
        } else {
            $obQuery->where('price', '>', $sData);
        }

        return $obQuery;
    }

    /**
     * Get by min old price
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @param $sData
     * @param $bFlag
     * @return mixed
     */
    public function scopeGetByMinOldPrice($obQuery, $sData, $bFlag = false)
    {
        if(empty($sData)) {
            $sData = 0;
        }

        if ($bFlag) {
            $obQuery->where('old_price', '>=', $sData);
        } else {
            $obQuery->where('old_price', '>', $sData);
        }

        return $obQuery;
    }

    /**
     * Get by max price
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @param $sData
     * @param $bFlag
     * @return mixed
     */
    public function scopeGetByMaxPrice($obQuery, $sData, $bFlag = false)
    {
        if(empty($sData)) {
            $sData = 0;
        }

        if ($bFlag) {
            $obQuery->where('price', '<=', $sData);
        } else {
            $obQuery->where('price', '<', $sData);
        }

        return $obQuery;
    }

    /**
     * Get by code (LIKE)
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @param $sData
     * @return mixed
     */
    public function scopeLikeByCode($obQuery, $sData)
    {

        if(!empty($sData)) {
            $obQuery->where('code', 'like', $sData.'%');
        }

        return $obQuery;
    }

    /**
     * Get offer data
     * @return  array|void
     */
    public function getData() {

        $arResult = [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'preview_text' => $this->preview_text,
            'preview_image' => $this->getImage($this->preview_image),
            'description' => $this->description,
            'images' => $this->getImageList($this->images),
            'price' => $this->price,
            'old_price' => $this->old_price,
            'price_value' => $this->getPriceValue(),
            'old_price_value' => $this->getOldPriceValue(),
            'quantity' => $this->quantity,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_slug' => $this->product->slug,
        ];

        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            $arResult['properties'] = \Lovata\PropertiesShopaholic\Classes\CProperty::getOfferPropertiesList($this);
        }

        if(PluginManager::instance()->hasPlugin('Lovata.CustomShopaholic')) {
            \Lovata\CustomShopaholic\Plugin::addOfferData($arResult, $this);
        }

        return $arResult;
    }

    /**
     * Get cached product data
     * @param int $iOfferID
     * @param Offer $obOffer
     * @return array|null|string|void
     */
    public static function getCacheData($iOfferID, $obOffer = null) {

        if(empty($iOfferID)) {
            return [];
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT];
        $sCacheKey = $iOfferID;

        $arResult = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arResult)) {
            return $arResult;
        }

        //Get offer object
        if(empty($obOffer)) {
            $obOffer = Offer::find($iOfferID);
        }

        if(empty($obOffer)) {
            return [];
        }

        $arResult = $obOffer->getData();

        //Set cache data
        $iCacheTime = Settings::getCacheTime('cache_time_product');
        CCache::put($arCacheTags, $sCacheKey, $arResult, $iCacheTime);

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
     * Clear cache
     */
    public function clearCache() {

        $this->checkActiveProduct();
        
        //Clear offer data
        CCache::clear([Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT], $this->id);

        //Clear product data
        $obProduct = $this->product;
        if(!empty($obProduct)) {
            $obProduct->clearCache();
        }
        
        Event::fire(self::CACHE_TAG_ELEMENT.'.cache.clear', [$this]);
    }
}