<?php namespace Lovata\Shopaholic\Models;

use Kharanenka\Helper\CustomValidationMessage;
use Kharanenka\Helper\DataFileModel;
use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CategoryBelongsTo;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Scope\SlugField;
use Lovata\Shopaholic\Classes\ProductListStore;
use Lovata\Shopaholic\Components\Breadcrumbs;
use Model;
use Event;
use Carbon\Carbon;
use October\Rain\Database\Builder;
use October\Rain\Database\Relations\BelongsToMany;
use October\Rain\Database\Relations\HasMany;
use October\Rain\Database\Traits\SoftDelete;
use System\Classes\PluginManager;
use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Plugin;
use Lovata\Toolbox\Plugin as ToolboxPlugin;
use October\Rain\Database\Collection;
use \October\Rain\Database\Traits\Validation;

/**
 * Class Product
 * @package Lovata\Shopaholic\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * 
 * @mixin Builder
 * @mixin \Eloquent
 * @mixin \Lovata\SeoShopaholic\Classes\ModelExtend
 * @mixin \Lovata\GoodNewsShopaholic\Classes\ProductExtend
 * @mixin \Lovata\CustomShopaholic\Classes\ProductExtend
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
 * //TODO: Перенести описание свойств
 * "Related products for shopaholic"
 * @property Collection|Product[] $rel_products
 * @method  Builder|\Eloquent|BelongsToMany rel_products()
 * 
 * "Recommend for Shopaholic" plugin fields
 * @property bool $recommend
 *
 * @method static $this getByBrandName(string $sBrandName)
 * @method static $this likeByBrandName(string $sBrandName)
 */
class Product extends Model
{
    use Validation;
    use SoftDelete;
    use ActiveField;
    use NameField;
    use CategoryBelongsTo;
    use SlugField;
    use CodeField;
    use ExternalIDField;
    use CustomValidationMessage;
    use DataFileModel;

    const CACHE_TAG_ELEMENT = 'shopaholic-product-element';
    const CACHE_TAG_LIST = 'shopaholic-product-list';

    public $table = 'lovata_shopaholic_products';
  
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_products',
    ];

    public $customMessages = [];
    public $attributeNames = [];
    
    public $belongsTo = [
        'category' => ['Lovata\Shopaholic\Models\Category'],
        'brand' => ['Lovata\Shopaholic\Models\Brand'],
    ];

    public $hasMany = [
        'offers' => ['Lovata\Shopaholic\Models\Offer'],
    ];

    public $appends = [];
    
    public $fillable = [
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

    public $dates = ['created_at', 'created_at', 'deleted_at'];

    /**
     * Product constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $iPreviewTextMaxLength = (int) Settings::getValue('product_preview_limit_max');
        if($iPreviewTextMaxLength > 0) {
            $this->rules['preview_text'] = 'max:'.$iPreviewTextMaxLength;
        }

        $this->setCustomMessage(ToolboxPlugin::NAME, ['required', 'unique', 'max.string']);
        $this->setCustomAttributeName(ToolboxPlugin::NAME, ['name', 'slug', 'preview_text']);

        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            \Lovata\PropertiesShopaholic\Classes\ProductExtend::constructExtend($this);
        }

        if(PluginManager::instance()->hasPlugin('Lovata.SeoShopaholic')) {
            \Lovata\SeoShopaholic\Classes\ModelExtend::constructExtend($this);
        }

        if(PluginManager::instance()->hasPlugin('Lovata.GoodNewsShopaholic')) {
            \Lovata\GoodNewsShopaholic\Classes\ProductExtend::constructExtend($this);
        }

        if(PluginManager::instance()->hasPlugin('Lovata.CustomShopaholic')) {
            \Lovata\CustomShopaholic\Classes\ProductExtend::constructExtend($this);
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
     * Get by brand name
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @param $sData
     * @return mixed
     */
    public function scopeLikeByBrandName($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->whereHas('brand', function($obQuery) use ($sData) {
                /** @var Brand $obQuery */
                $obQuery->active()->where('name', 'like', '%'.$sData.'%');
            });
        }

        return $obQuery;
    }

    /**
     * Get by brand name
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @param $sData
     * @return mixed
     */
    public function scopeGetByBrandName($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->whereHas('brand', function($obQuery) use ($sData) {
                /** @var Brand $obQuery */
                $obQuery->active()->where('name', $sData);
            });
        }

        return $obQuery;
    }

    /**
     * Check active offers
     * @return bool
     */
    public function checkActiveOffers()
    {
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
        $this->clearCache();
        ProductListStore::updateCacheAfterSave($this);
        Event::fire(self::CACHE_TAG_ELEMENT.'.after.save', [$this]);
    }

    public function afterDelete()
    {
        $this->clearCache();
        ProductListStore::updateCacheAfterDelete($this);
        Event::fire(self::CACHE_TAG_ELEMENT.'.after.delete', [$this]);
    }
    
    public function clearCache()
    {
        //Clear product data
        CCache::clear([Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT], $this->id);

        //Clear breadcrumbs
        CCache::clear([Plugin::CACHE_TAG, Breadcrumbs::CACHE_TAG, self::CACHE_TAG_ELEMENT, Category::CACHE_TAG_ELEMENT.'_'.$this->category_id], $this->id);
    }
    
    /**
     * Get addition property values
     * @return array
     */
    public function getPropertyAttribute()
    {
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
    public function setPropertyAttribute($arProperties)
    {
        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            \Lovata\PropertiesShopaholic\Classes\CProperty::setProductPropertiesValues($arProperties, $this);
        }
    }

    /**
     * Get product data
     * @return array
     */
    public function getData()
    {
        $arResult = [
            'id'                => $this->id,
            'name'              => $this->name,
            'slug'              => $this->slug,
            'code'              => $this->code,
            'category_id'       => $this->category_id,
            'preview_text'      => $this->preview_text,
            'preview_image'     => $this->getFileData('preview_image'),
            'description'       => $this->description,
            'images'            => $this->getFileListData('images'),
            'offer'             => [],
            'offer_id'          => $this->offers->lists('id'),
        ];

        //Get related products ID
        if(PluginManager::instance()->hasPlugin('Lovata.RelatedProductsShopaholic')) {
            $arResult['related_product'] = $this->rel_products()->lists('id');
        }

        //Get recommend flag 
        if(PluginManager::instance()->hasPlugin('Lovata.RecommendShopaholic')) {
            $arResult['recommend'] = $this->recommend;
        }
        
        //Get product properties
        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            $arResult['property'] = \Lovata\PropertiesShopaholic\Classes\CProperty::getProductPropertiesList($this);
        }

        if(PluginManager::instance()->hasPlugin('Lovata.CustomShopaholic')) {
            \Lovata\CustomShopaholic\Classes\ProductExtend::getData($arResult, $this);
        }

        return $arResult;
    }

    /**
     * Get cached product data
     * @param $iElementID
     * @param null|Product $obElement
     * @return array|null
     */
    public static function getCacheData($iElementID, $obElement = null)
    {
        if(empty($iElementID)) {
            return null;
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT];
        $sCacheKey = $iElementID;

        $arResult = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arResult)) {
            
            //Get product object
            if(empty($obElement)) {
                $obElement = self::active()->find($iElementID);
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
        
        //Get offer cached data
        if(!empty($arResult['offer_id'])) {
            foreach($arResult['offer_id'] as $iOfferID) {
                
                $arOfferData = Offer::getCacheData($iOfferID);
                if(empty($arOfferData)) {
                    continue;
                }
                
                $arResult['offer'][$iOfferID] = $arOfferData;
            }
        }

        if(PluginManager::instance()->hasPlugin('Lovata.CustomShopaholic'))
        {
            \Lovata\CustomShopaholic\Classes\ProductExtend::getCachedData($arResult);
        }

        return $arResult;
    }

    /**
     * Get fields list for backend interface with switching visibility
     * @return array
     */
    public static function getConfiguredBackendFields()
    {
        return [
            'code'                  => 'lovata.toolbox::lang.field.code',
            'external_id'           => 'lovata.toolbox::lang.field.external_id',
            'category'              => 'lovata.toolbox::lang.field.category',
            'brand'                 => 'lovata.shopaholic::lang.field.brand',
            'preview_text'          => 'lovata.toolbox::lang.field.preview_text',
            'description'           => 'lovata.toolbox::lang.field.description',
            'preview_image'         => 'lovata.toolbox::lang.field.preview_image',
            'images'                => 'lovata.toolbox::lang.field.images',
        ];
    }
}