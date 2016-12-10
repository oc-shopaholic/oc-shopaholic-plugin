<?php namespace Lovata\Shopaholic\Models;

use Kharanenka\Helper\CustomValidationMessage;
use Kharanenka\Helper\DataFileModel;
use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Scope\SlugField;
use Lovata\Shopaholic\Components\Breadcrumbs;
use Model;
use Event;
use Kharanenka\Helper\CCache;
use October\Rain\Database\Builder;
use October\Rain\Database\Collection;
use System\Classes\PluginManager;
use Lovata\Shopaholic\Plugin;
use Lovata\Toolbox\Plugin as ToolboxPlugin;
use \October\Rain\Database\Traits\Validation;
use \October\Rain\Database\Traits\NestedTree;

/**
 * Class Category
 * @package Lovata\Shopaholic\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * 
 * @mixin Builder
 * @mixin \Eloquent
 * @mixin \Lovata\SeoShopaholic\Classes\ModelExtend
 * @mixin \Lovata\PropertiesShopaholic\Classes\CategoryExtend
 * @mixin \Lovata\CustomShopaholic\Classes\CategoryExtend
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
 * @property Collection|\System\Models\File[] $images
 * @property Collection|Product[] $products
 * @property int $parent_id
 * @property int $nest_left
 * @property int $nest_right
 * @property int $nest_depth
 * @property Category $parent
 * @property Collection|Category[] $children
 *
 * @method static $this getByParentID(int $iParentID)
 */
class Category extends Model
{
    use Validation;
    use NestedTree;
    use ActiveField;
    use NameField;
    use SlugField;
    use CodeField;
    use ExternalIDField;
    use CustomValidationMessage;
    use DataFileModel;
    
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

    public $attachMany = [
        'images' => 'System\Models\File'
    ];

    public $fillable = [
        'name',
        'slug',
        'active',
        'code',
        'external_id',
        'preview_text',
        'description',
    ];

    public $casts = [];
    
    public $belongsToMany = [];
    public $hasMany = [
        'products' => 'Lovata\Shopaholic\Models\Product'
    ];

    /**
     * Category constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $iPreviewTextMaxLength = (int) Settings::getValue('category_preview_limit_max');
        if($iPreviewTextMaxLength > 0) {
            $this->rules['preview_text'] = 'max:'.$iPreviewTextMaxLength;
        }

        $this->setCustomMessage(ToolboxPlugin::NAME, ['required', 'unique', 'max.string']);
        $this->setCustomAttributeName(ToolboxPlugin::NAME, ['name', 'slug', 'preview_text']);
        
        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            \Lovata\PropertiesShopaholic\Classes\CategoryExtend::constructExtend($this);
        }

        if(PluginManager::instance()->hasPlugin('Lovata.SeoShopaholic')) {
            \Lovata\SeoShopaholic\Classes\ModelExtend::constructExtend($this);
        }

        if(PluginManager::instance()->hasPlugin('Lovata.GoodNewsShopaholic')) {
            \Lovata\GoodNewsShopaholic\Classes\CategoryExtend::constructExtend($this);
        }

        if(PluginManager::instance()->hasPlugin('Lovata.CustomShopaholic')) {
            \Lovata\CustomShopaholic\Classes\CategoryExtend::constructExtend($this);
        }

        //TODO: Перенести в плагин тегов
        if(PluginManager::instance()->hasPlugin('Lovata.TagsShopaholic')) {
            $this->hasMany['tags'] = [
                'Lovata\TagsShopaholic\Models\Tag',
                'table' => 'lovata_tagsshopaholic_tags',
            ];
        }

        parent::__construct($attributes);
    }
    
    public function beforeDelete()
    {
        if(PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            \Lovata\PropertiesShopaholic\Classes\CategoryExtend::beforeDeleteExtend($this);
        }
    }
    
    public function afterSave()
    {
        $this->cacheClear();
        Event::fire(self::CACHE_TAG_ELEMENT.'.after.save', [$this]);
    }

    public function afterDelete()
    {
        $this->cacheClear();
        Event::fire(self::CACHE_TAG_ELEMENT.'.after.delete', [$this]);
    }

    /**
     * Clear cache
     */
    protected function cacheClear()
    {
        //Clear breadcrumbs
        CCache::clear([Plugin::CACHE_TAG, Breadcrumbs::CACHE_TAG, self::CACHE_TAG_ELEMENT]);
        CCache::clear([Plugin::CACHE_TAG, Breadcrumbs::CACHE_TAG, Product::CACHE_TAG_ELEMENT, self::CACHE_TAG_ELEMENT.'_'.$this->id]);

        //Clear category data
        CCache::clear([Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT], $this->id);

        //Clear category list
        CCache::clear([Plugin::CACHE_TAG], self::CACHE_TAG_LIST);
        Event::fire(self::CACHE_TAG_ELEMENT.'.cache.clear', [$this]);
    }

    /**
     * Get by parent ID
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @param $sData
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetByParentID($obQuery, $sData)
    {
        return $obQuery->where('parent_id', $sData);
    }

    /**
     * Get category data
     * @return array
     */
    public function getData()
    {
        $arResult = [
            'id'                => $this->id,
            'name'              => $this->name,
            'slug'              => $this->slug,
            'code'              => $this->code,
            'preview_text'      => $this->preview_text,
            'description'       => $this->description,
            'nest_depth'        => $this->getDepth(),
            'preview_image'     => $this->getFileData('preview_image'),
            'images'            => $this->getFileListData('images'),
            'children'          => $this->getChildrenCategories(),
        ];

        if(PluginManager::instance()->hasPlugin('Lovata.CustomShopaholic')) {
            \Lovata\CustomShopaholic\Classes\CategoryExtend::getData($arResult, $this);
        }
        
        return $arResult;
    }

    /**
     * Get children categories
     * @return array
     */
    protected function getChildrenCategories()
    {
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
     * @param int $iElementID
     * @param null|Category $obElement
     * @return array|null
     */
    public static function getCacheData($iElementID, $obElement = null)
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT];
        $sCacheKey = $iElementID;

        $arResult = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arResult)) {

            //Get category object
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

        if(PluginManager::instance()->hasPlugin('Lovata.CustomShopaholic')) {
            \Lovata\CustomShopaholic\Classes\CategoryExtend::getCachedData($arResult);
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
            'preview_text'          => 'lovata.toolbox::lang.field.preview_text',
            'description'           => 'lovata.toolbox::lang.field.description',
            'preview_image'         => 'lovata.toolbox::lang.field.preview_image',
            'images'                => 'lovata.toolbox::lang.field.images',
        ];
    }
}