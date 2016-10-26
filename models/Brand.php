<?php namespace Lovata\Shopaholic\Models;

use Lang;
use Kharanenka\Helper\CCache;
use Model;
use Lovata\Shopaholic\Classes\CommonTrait;
use October\Rain\Database\Builder;
use October\Rain\Database\Collection;
use System\Classes\PluginManager;

use Lovata\Shopaholic\Plugin;
use Event;

/**
 * Class Brand
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
 * @property \System\Models\File $preview_image
 *
 * "SEO for Shopaholic" plugin fields
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * 
 * @property Collection|Product[] $products
 *
 * @method static $this getByCategory(int $iCategoryID)
 */
class Brand extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    use CommonTrait;

    const CACHE_TAG_ELEMENT = 'shopaholic-brand-element';
    const CACHE_TAG_LIST = 'shopaholic-brand-list';

    public $table = 'lovata_shopaholic_brands';
    
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_brands',
    ];
    
    public $customMessages = [];
    public $attributeNames = [];


    public $attachOne = [
        'preview_image' => 'System\Models\File'
    ];

    protected $fillable = [
        'name',
        'slug',
        'active',
        'code',
        'external_id',
        'preview_text',
        'description',
    ];

    public $hasMany = [
        'products' => 'Lovata\Shopaholic\Models\Product'
    ];
    
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

        if(PluginManager::instance()->hasPlugin('Lovata.SeoShopaholic')){
            $this->fillable = array_merge($this->fillable, \Lovata\SeoShopaholic\Plugin::getSEOFieldsList());
        }
        
        parent::__construct($attributes);
    }

    public function afterSave()
    {
        $this->clearCache();
    }

    public function afterDelete()
    {
        $this->clearCache();
    }

    /**
     * return preview image path
     * @return string|void
     */
    public function getPreviewImage()
    {
        //TODO: Перенести в пакет
        return self::getImage($this->preview_image);
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
            $obQuery->whereHas('products', function($obQuery) use ($sData) {
                /** @var Product $obQuery */
                $obQuery->active()->getByCategory($sData);
            });
        }

        return $obQuery;
    }

    /**
     * Get brand data
     * @return array
     */
    public function getData() {
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'preview_text' => $this->preview_text,
            'description' => $this->description,
            'preview_image' => $this->getPreviewImage(),
        ];
    }

    /**
     * Get cached product data
     * @param int $iBrandID
     * @param null|Brand $obBrand
     * @return array|null|string|void
     */
    public static function getCacheData($iBrandID, $obBrand = null) {

        if(empty($iBrandID)) {
            return [];
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT];
        $sCacheKey = $iBrandID;

        $arResult = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arResult)) {
            return $arResult;
        }

        //Get product object
        if(empty($obBrand)) {
            $obBrand = Brand::find($iBrandID);
        }

        if(empty($obBrand)) {
            return [];
        }

        $arResult = $obBrand->getData();

        //Set cache data
        $iCacheTime = Settings::getCacheTime('cache_time_product');
        CCache::put($arCacheTags, $sCacheKey, $arResult, $iCacheTime);

        return $arResult;
    }
    
    /**
     * Clear cache
     */
    protected function clearCache()
    {
        CCache::clear([Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT], $this->id);
        Event::fire(self::CACHE_TAG_ELEMENT.'.cache.clear', [$this]);
    }
}