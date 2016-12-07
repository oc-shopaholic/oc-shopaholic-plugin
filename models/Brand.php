<?php namespace Lovata\Shopaholic\Models;

use Kharanenka\Helper\CustomValidationMessage;
use Kharanenka\Helper\DataFileModel;
use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Helper\CCache;
use Kharanenka\Scope\SlugField;
use Model;
use October\Rain\Database\Builder;
use October\Rain\Database\Collection;
use October\Rain\Database\Relations\HasMany;
use System\Classes\PluginManager;

use Lovata\Shopaholic\Plugin;
use Lovata\Toolbox\Plugin as ToolboxPlugin;
use Event;

/**
 * Class Brand
 * @package Lovata\Shopaholic\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin Builder
 * @mixin \Eloquent
 * @mixin \Lovata\SeoShopaholic\Classes\ModelExtend
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
 *
 * @property Collection|Product[] $products
 * @method Builder|\Eloquent|HasMany products()
 * 
 * @method static $this getByCategory(int $iCategoryID)
 */
class Brand extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    use ActiveField;
    use NameField;
    use CodeField;
    use SlugField;
    use ExternalIDField;
    use CustomValidationMessage;
    use DataFileModel;

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

    public $hasMany = [
        'products' => 'Lovata\Shopaholic\Models\Product'
    ];
    
    public function __construct(array $attributes = [])
    {
        $this->setCustomMessage(ToolboxPlugin::NAME, ['required', 'unique']);
        $this->setCustomAttributeName(ToolboxPlugin::NAME, ['name', 'slug']);

        if(PluginManager::instance()->hasPlugin('Lovata.SeoShopaholic')) {
            \Lovata\SeoShopaholic\Classes\ModelExtend::constructExtend($this);
        }

        if(PluginManager::instance()->hasPlugin('Lovata.CustomShopaholic')) {
            \Lovata\CustomShopaholic\Classes\BrandExtend::constructExtend($this);
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
     * Clear cache
     */
    protected function clearCache()
    {
        CCache::clear([Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT], $this->id);
        Event::fire(self::CACHE_TAG_ELEMENT.'.cache.clear', [$this]);
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
        
        $arResult = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'preview_text' => $this->preview_text,
            'description' => $this->description,
            'preview_image' => $this->getFileData('preview_image'),
            'images' => $this->getFileListData('images'),
        ];

        if(PluginManager::instance()->hasPlugin('Lovata.CustomShopaholic')) {
            \Lovata\CustomShopaholic\Classes\BrandExtend::getDataExtend($arResult, $this);
        }

        return $arResult;
    }

    /**
     * Get cached brand data
     * @param int $iElementID
     * @param null|Brand $obElement
     * @return array|null|string|void
     */
    public static function getCacheData($iElementID, $obElement = null) {

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

        if(PluginManager::instance()->hasPlugin('Lovata.CustomShopaholic')) {
            \Lovata\CustomShopaholic\Classes\BrandExtend::getCachedDataExtend($arResult);
        }

        return $arResult;
    }

    /**
     * Get fields list for backend interface with switching visibility
     * @return array
     */
    public static function getConfiguredBackendFields() {
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