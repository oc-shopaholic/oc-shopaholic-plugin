<?php namespace Lovata\Shopaholic\Models;

use Lang;
use Lovata\Shopaholic\Components\Breadcrumbs;
use Model;
use Event;
use Carbon\Carbon;
use October\Rain\Database\Builder;
use October\Rain\Database\Relations\BelongsToMany;
use October\Rain\Database\Relations\HasMany;
use System\Classes\PluginManager;
use Lovata\Shopaholic\Classes\CommonTrait;
use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Plugin;
use October\Rain\Database\Collection;

/**
 * Class Product
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
 * @property int $category_id
 * @property int $brand_id
 * @property string $external_id
 * @property string $preview_text
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property \System\Models\File $preview_image
 * @property Collection|\System\Models\File[] $images
 * @property Category $category
 * @property Brand $brand
 * @property Collection|Offer[] $offers
 * @method Builder|\Eloquent|HasMany offers()
 * 
 * "Related products for shopaholic"
 * @property Collection|Product[] $rel_products
 * @method  Builder|\Eloquent|BelongsToMany rel_products()
 *
 *  "SEO for Shopaholic" plugin fields
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * 
 * "Recommend for Shopaholic" plugin fields
 * @property bool $recommend
 * 
 * "Good news" plugin fields
 * @property Collection|\Lovata\GoodNews\Models\Article[] $article
 *
 * @method static $this hasActiveOffer()
 * @method static $this getByCategory(int $iCategoryID)
 * @method static $this getByBrandSlug(string $sBrandSlug)
 * @method static $this getByBrandName(string $sBrandName)
 * @method static $this likeByBrandName(string $sBrandName)
 * @method static $this likeByName(string $sName)
 * @method static $this likeByCode(string $sCode)
 * @method static $this getByMinQuantity(int $iCount, bool $bFlag = false)
 * @method static $this getByMinPrice(int $iPrice, bool $bFlag = false)
 * @method static $this getByMaxPrice(int $iPrice, bool $bFlag = false)
 */
class Product extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDeleting;
    use CommonTrait;

    const CACHE_TAG_ELEMENT = 'shopaholic-product-element';
    const CACHE_TAG_LIST = 'shopaholic-product-list';
    
    /**
     * Validation
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_products',
    ];

    public $customMessages = [];
    public $attributeNames = [];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'lovata_shopaholic_products';

    public $belongsTo = [
        'category' => ['Lovata\Shopaholic\Models\Category'],
        'brand' => ['Lovata\Shopaholic\Models\Brand'],
    ];

    public $hasMany = [
        'offers' => ['Lovata\Shopaholic\Models\Offer'],
    ];

    public $appends = [];
    
    protected $fillable = [
        'name',
        'slug',
        'active',
        'code',
        'external_id',
        'preview_text',
        'description',
        'brand_id',
        'category_id',
    ];

    public $attachOne = [
        'preview_image' => 'System\Models\File'
    ];

    public $attachMany = [
        'images' => 'System\Models\File'
    ];

    protected $dates = ['created_at', 'created_at', 'deleted_at'];

    public function __construct(array $attributes = [])
    {

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

        if(PluginManager::instance()->hasPlugin('Lovata.SeoShopaholic')){
            $this->fillable = array_merge($this->fillable, \Lovata\SeoShopaholic\Plugin::getSEOFieldsList());
        }

        //TODO: перенести связь в плагин
        if(PluginManager::instance()->hasPlugin('Lovata.RelatedProductsShopaholic')) {
            $this->belongsToMany['rel_products'] = [
                'Lovata\Shopaholic\Models\Product',
                'table'     => 'lovata_relatedproductsshopaholic_prod_rel',
                'key'       => 'prod_id',
                'otherKey'  => 'rel_id',
            ];
        }

        //TODO: перенести связь в плагин
        if(PluginManager::instance()->hasPlugin('Lovata.GoodNewsShopaholic')) {
            $this->belongsToMany['article'] = [
                'Lovata\GoodNews\Models\Article',
                'table'     => 'lovata_goodnewsshopaholic_product_link',
                'key'       => 'product_id',
                'otherKey'  => 'article_id',
            ];
        }

        //TODO: перенести связь в плагин
        if(PluginManager::instance()->hasPlugin('Lovata.TagsShopaholic')) {
            $this->belongsToMany['tags'] = [
                'Lovata\TagsShopaholic\Models\Tag',
                'table' => 'lovata_tagsshopaholic_tagproduct',
                'key'       => 'product_id',
                'otherKey'  => 'tag_id',
            ];
        }

        parent::__construct($attributes);
    }

    /**
     * Check active offers
     * @return bool
     */
    public function checkActiveOffers() {
        
        //Check active offers
        $arOffers = $this->offers;
        
        if(!$arOffers->isEmpty()) {
            /** @var Offer $obOffer */
            foreach($arOffers as $obOffer) {
                if($obOffer->active) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    public function afterSave()
    {
        Event::fire(self::CACHE_TAG_ELEMENT.'.after.save', [$this]);
        $this->clearCache();
    }

    public function afterDelete()
    {
        Event::fire(self::CACHE_TAG_ELEMENT.'.after.delete', [$this]);
        $this->clearCache();
    }

    //TODO: Перенести в пакет
    /**
     * Get by category ID
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @param $sData
     * @return mixed
     */
    public function scopeGetByCategory($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->where('category_id', $sData);
        }
        
        return $obQuery;
    }

    /**
     * Get by min count
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasActiveOffer($obQuery)
    {

        $obQuery->whereHas('offers', function($obQuery) {
            /** @var Offer $obQuery */
            $obQuery->active();
        });

        return $obQuery;
    }

    /**
     * Get by name (LIKE)
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @param $sData
     * @return mixed
     */
    public function scopeLikeByName($obQuery, $sData)
    {

        if(!empty($sData)) {
            $obQuery->where('name', 'like', '%'.$sData.'%');
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

        $obQuery->whereHas('offers', function($obQuery) use ($sData, $bFlag) {
            /** @var Offer $obQuery */
            $obQuery->active()->getByMinPrice($sData, $bFlag);
        });

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

        $obQuery->whereHas('offers', function($obQuery) use ($sData, $bFlag) {
            /** @var Offer $obQuery */
            $obQuery->active()->getByMaxPrice($sData, $bFlag);
        });

        return $obQuery;
    }
    
    /**
     * Get addition property values
     * @return array
     */
    public function getPropertyAttribute() {
        
        $arResult = [];
        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            $arResult = \Lovata\PropertiesShopaholic\Classes\CProperty::getProductPropertiesValues($this);
        }
        
        return $arResult;
    }

    /**
     * Set addition property values
     * @param $arProperties
     */
    public function setPropertyAttribute($arProperties) {

        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            \Lovata\PropertiesShopaholic\Classes\CProperty::setProductPropertiesValues($arProperties, $this);
        }
    }

    /**
     * Get product data
     * @return array
     */
    public function getData() {
        
        $arResult = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'category_id' => $this->category_id,
            'preview_text' => $this->preview_text,
            'preview_image' => $this->getImage($this->preview_image),
            'description' => $this->description,
            'images' => $this->getImageList($this->images),
            'brand' => [],
            'offers' => [],
            'related_products' => [],
        ];
        
        /** @var Brand $obBrand */
        $obBrand = $this->brand;
        if(!empty($obBrand)) {
            $arResult['brand'] = [
                'id' => $obBrand->id,
                'name' => $obBrand->name,
                'slug' => $obBrand->slug,
                'code' => $obBrand->code,
            ];
        }
        
        /** @var Offer $obOffer */
        $obOffers = $this->offers;
        if(!$obOffers->isEmpty()) {
            foreach ($obOffers as $obOffer) {
                if(!$obOffer->active) {
                    continue;
                }

                $arOfferData = Offer::getCacheData($obOffer->id, $obOffer);
                $arResult['offers'][$obOffer->id] = $arOfferData;

                //TODO: Вынести логику из плагина в кастом
                //Generate offer property value list
                if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic') && !empty($arOfferData['properties'])) {
                    if(!isset($arResult['offer_properties'])) {
                        $arResult['offer_properties'] = [];
                    }

                    foreach ($arOfferData['properties'] as $iPropertyID => $arProperty) {

                        if($arProperty['type'] != \Lovata\PropertiesShopaholic\Models\Property::TYPE_CHECKBOX) {
                            $arProperty['value'] = [$arProperty['value']];
                        }

                        //Add property to list
                        if(!isset($arResult['offer_properties'][$iPropertyID])) {
                            $arResult['offer_properties'][$iPropertyID] = $arProperty;
                        } else {
                            $arResult['offer_properties'][$iPropertyID]['value'] = array_merge($arResult['offer_properties'][$iPropertyID]['value'], $arProperty['value']);
                        }
                    }
                }
            }
        }

        //TODO: Вынести кастомную логику
        //Sorting offer property values
        if(!empty($arResult['offer_properties'])) {
            foreach($arResult['offer_properties'] as &$arProperty) {
                $arProperty['value'] = array_unique($arProperty['value']);
                natsort($arProperty['value']);

                usort($arProperty['value'], function($sValuePrev, $sValueNext) {
                    $sValuePrev = str_replace(',', '.', $sValuePrev);
                    $sValueNext = str_replace(',', '.', $sValueNext);
                    $sValuePrev = (float) $sValuePrev;
                    $sValueNext = (float) $sValueNext;
                    
                    if($sValueNext == $sValuePrev) {
                        return 0;
                    }
                    
                    if($sValueNext > $sValuePrev) {
                        return -1;
                    }
                    
                  return 1;
                });
            }
        }

        //Sorting get related products
        if(PluginManager::instance()->hasPlugin('Lovata.RelatedProductsShopaholic')) {
            $arResult['related_product_id'] = $this->rel_products()->lists('id');
        }

        if(PluginManager::instance()->hasPlugin('Lovata.RecommendShopaholic')) {
            $arResult['recommend'] = $this->recommend;
        }
        
        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            $arResult['properties'] = \Lovata\PropertiesShopaholic\Classes\CProperty::getProductPropertiesList($this);
        }

        return $arResult;
    }

    /**
     * Get cached product data
     * @param $iProductID
     * @param null|Product $obProduct
     * @return array|null|string|void
     */
    public static function getCacheData($iProductID, $obProduct = null) {
        
        if(empty($iProductID)) {
            return [];
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT];
        $sCacheKey = $iProductID;

        $arResult = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arResult)) {
            return $arResult;
        }
        
        //Get product object
        if(empty($obProduct)) {
            $obProduct = Product::find($iProductID);
        }
        
        if(empty($obProduct)) {
            return [];
        }
        
        $arResult = $obProduct->getData();

        //Set cache data
        $iCacheTime = Settings::getCacheTime('cache_time_product');
        CCache::put($arCacheTags, $sCacheKey, $arResult, $iCacheTime);

        return $arResult;
    }

    /**
     * Clear cache
     * @param $bEventFire
     */
    public function clearCache($bEventFire = true) {
        
        //Clear product data
        CCache::clear([Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT], $this->id);
        
        //Clear breadcrumbs
        CCache::clear([Plugin::CACHE_TAG, Breadcrumbs::CACHE_TAG, self::CACHE_TAG_ELEMENT, Category::CACHE_TAG_ELEMENT.'_'.$this->category_id], $this->id);

        if($bEventFire) {
            Event::fire(self::CACHE_TAG_ELEMENT.'.cache.clear', [$this]);
        }
    }
}