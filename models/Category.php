<?php namespace Lovata\Shopaholic\Models;

use Lang;
use Lovata\Shopaholic\Components\Breadcrumbs;
use Model;
use Event;
use Kharanenka\Helper\CCache;
use October\Rain\Database\Builder;
use October\Rain\Database\Collection;
use October\Rain\Database\Relations\BelongsToMany;
use System\Classes\PluginManager;
use Lovata\Shopaholic\Classes\CommonTrait;
use Lovata\Shopaholic\Plugin;

/**
 * Class Category
 * @package Lovata\Shopaholic\Models
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
 * @property Collection|Product[] $products
 * @property int $parent_id
 * @property int $nest_left
 * @property int $nest_right
 * @property int $nest_depth
 * @property Category $parent
 * @property Collection|Category[] $children
 *
 * "Properties for Shopaholic" plugin fields
 * @property Collection|Lovata\PropertiesShopaholic\Models\Property[] $product_property
 * @property Collection|Lovata\PropertiesShopaholic\Models\Property[] $offer_property
 * @method Builder|\Eloquent|BelongsToMany product_property()
 * @method Builder|\Eloquent|BelongsToMany offer_property()
 * 
 * "SEO for Shopaholic" plugin fields
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * 
 * @method static $this likeByName(string $sName)
 * @method static $this getByParentID(int $iParentID)
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\NestedTree;
    use CommonTrait;
    
    const CACHE_TAG_ELEMENT = 'shopaholic-category-element';
    const CACHE_TAG_LIST = 'shopaholic-category-list';

    public $table = 'lovata_shopaholic_categories';
    
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_categories',
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

    public $belongsToMany = [];
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
        
        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            
            $arPivotData = ['groups'];
            if(PluginManager::instance()->hasPlugin('Lovata.FilterShopaholic')) {
                $arPivotData = array_merge($arPivotData, \Lovata\FilterShopaholic\Classes\CFilter::getPivotList());
            }
            
            //TODO: Перенести логику в плагины
            //Add relation with addition property
            $this->belongsToMany['product_property'] = [
                'Lovata\PropertiesShopaholic\Models\Property',
                'table'     => 'lovata_propertiesshopaholic_product_link',
                'key'       => 'category_id',
                'otherKey'  => 'property_id',
                'delete'    => true,
                'order'     => 'sort_order asc',
                'pivot'     => $arPivotData,
                'pivotModel'=> 'Lovata\PropertiesShopaholic\Models\PropertyProductLink',
            ];

            $this->belongsToMany['offer_property'] = [
                'Lovata\PropertiesShopaholic\Models\Property',
                'table'     => 'lovata_propertiesshopaholic_offer_link',
                'key'       => 'category_id',
                'otherKey'  => 'property_id',
                'delete'    => true,
                'order'     => 'sort_order asc',
                'pivot'     => $arPivotData,
                'pivotModel'=> 'Lovata\PropertiesShopaholic\Models\PropertyOfferLink',
            ];
        }

        if(PluginManager::instance()->hasPlugin('Lovata.SeoShopaholic')){
            $this->fillable = array_merge($this->fillable, \Lovata\SeoShopaholic\Plugin::getSEOFieldsList());
        }

        if(PluginManager::instance()->hasPlugin('Lovata.TagsShopaholic')) {
            $this->hasMany['tags'] = [
                'Lovata\TagsShopaholic\Models\Tag',
                'table' => 'lovata_tagsshopaholic_tags',
            ];
        }

        parent::__construct($attributes);
    }
    
    public function beforeDelete() {

        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            $this->product_property()->detach();
            $this->offer_property()->detach();
        }
    }
    
    public function afterSave() {
        $this->cacheClear();
    }

    public function afterDelete() {
        $this->cacheClear();
    }

    /**
     * Get by parent ID
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetByParentID($obQuery, $sData) {
        return $obQuery->where('parent_id', $sData);
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
     * Get category data
     * @return array|null|string
     */
    public function getData() {
        
        $arResult = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'preview_text' => $this->preview_text,
            'description' => $this->description,
            'image' => self::getImage($this->preview_image),
            'children' => $this->getChildrenCategories(),
            'slug_full' => self::getFullSlug($this),
            'count_product' => Product::active()->getByCategory($this->id)->count(),
        ];
        
        return $arResult;
    }

    /**
     * Get children categories
     * @return array
     */
    protected function getChildrenCategories() {

        $arChildrenData = [];

        //Get children category
        $arChildrenCategory = $this->children;
        if($arChildrenCategory->isEmpty()) {
            return $arChildrenData;
        }

        foreach($arChildrenCategory as $obChildrenCategory) {
            //Get category data
            $arChildrenData[$obChildrenCategory->id] = $obChildrenCategory->getData();
        }

        return $arChildrenData;
    }

    /**
     * Get category data from cache
     * @param int $iCategoryID
     * @param null|Category $obCategory
     * @return array|null|string
     */
    public static function getCacheData($iCategoryID, $obCategory = null) {

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT];
        $sCacheKey = $iCategoryID;

        $arResult = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arResult)) {
            return $arResult;
        }

        //Get category object
        if(empty($obCategory)) {
            $obCategory = Category::find($iCategoryID);
        }

        if(empty($obCategory)) {
            return [];
        }

        $arResult = $obCategory->getData();

        //Set cache data
        $iCacheTime = Settings::getCacheTime('cache_time_category');
        CCache::put($arCacheTags, $sCacheKey, $arResult, $iCacheTime);

        return $arResult;
    }
    
    /**
     * Get full slug for category
     * @param Category $obCategory
     * @param null $sSlug
     * @return string
     */
    public static function getFullSlug($obCategory, $sSlug = null) {

        if(empty($obCategory) || !$obCategory instanceof  Category) {
            return $sSlug;
        }

        $sSlug = $obCategory->slug.'/'.$sSlug;

        /** @var Category $obParentCategory */
        $obParentCategory = $obCategory->parent;
        if(!empty($obParentCategory)) {
            $sSlug = self::getFullSlug($obParentCategory, $sSlug);
        }

        return $sSlug;
    }

    /**
     * Clear cache
     */
    protected function cacheClear() {
        
        //Clear breadcrumbs
        CCache::clear([Plugin::CACHE_TAG, Breadcrumbs::CACHE_TAG, self::CACHE_TAG_ELEMENT]);
        CCache::clear([Plugin::CACHE_TAG, Breadcrumbs::CACHE_TAG, Product::CACHE_TAG_ELEMENT, self::CACHE_TAG_ELEMENT.'_'.$this->id]);
        
        //Clear category data
        CCache::clear([Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT], $this->id);
        
        //Clear category list
        CCache::clear([Plugin::CACHE_TAG], self::CACHE_TAG_LIST);

        Event::fire(self::CACHE_TAG_ELEMENT.'.cache.clear', [$this]);
    }
}