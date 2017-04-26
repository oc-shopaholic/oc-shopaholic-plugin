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
use Lovata\Toolbox\Traits\Helpers\TraitClassExtension;

use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\NestedTree;

/**
 * Class Category
 * @package Lovata\Shopaholic\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * 
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
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
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * Nested tree properties
 * @property int $parent_id
 * @property int $nest_left
 * @property int $nest_right
 * @property int $nest_depth
 * @property Category $parent
 * @property \October\Rain\Database\Collection|Category[] $children
 * @method static $this children()
 *
 * Relations
 * @property \System\Models\File $preview_image
 * @property \October\Rain\Database\Collection|\System\Models\File[] $images
 *
 * @property \October\Rain\Database\Collection|Product[] $products
 * @method Product|\October\Rain\Database\Relations\HasMany products()
 *
 * Properties for Shopaholic
 * @property \October\Rain\Database\Collection|\Lovata\PropertiesShopaholic\Models\Property[] $product_property
 * @method \Lovata\PropertiesShopaholic\Models\Property|\October\Rain\Database\Relations\BelongsToMany product_property()
 *
 * @property \October\Rain\Database\Collection|\Lovata\PropertiesShopaholic\Models\Property[] $offer_property
 * @method \Lovata\PropertiesShopaholic\Models\Property|\October\Rain\Database\Relations\BelongsToMany offer_property()
 *
 * Scope list
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
    use TraitClassExtension;
    
    const CACHE_TAG_ELEMENT = 'shopaholic-category-element';
    const CACHE_TAG_LIST = 'shopaholic-category-list';

    public $table = 'lovata_shopaholic_categories';
    
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_categories',
    ];
    
    public $customMessages = [];
    public $attributeNames = [];

    public $attachOne = ['preview_image' => 'System\Models\File'];
    public $attachMany = ['images' => 'System\Models\File'];
    public $belongsToMany = [];
    public $hasMany = ['products' => 'Lovata\Shopaholic\Models\Product'];

    public $fillable = [
        'name',
        'slug',
        'active',
        'code',
        'external_id',
        'preview_text',
        'description',
    ];

    public $dates = ['created_at', 'updated_at'];
    public $casts = [];

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
        parent::__construct($attributes);
    }

    /**
     * Before delete method
     */
    public function beforeDelete()
    {
        Event::fire(self::CACHE_TAG_ELEMENT.'.before.delete', [$this]);
    }

    /**
     * After save method
     */
    public function afterSave()
    {
        $this->cacheClear();
        Event::fire(self::CACHE_TAG_ELEMENT.'.after.save', [$this]);
    }

    /**
     * After delete method
     */
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
        //Clear category data
        CCache::clear([Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT], $this->id);

        //Clear category list
        CCache::clear([Plugin::CACHE_TAG], self::CACHE_TAG_LIST);
        Event::fire(self::CACHE_TAG_ELEMENT.'.cache.clear', [$this]);
    }

    /**
     * Get by parent ID
     * @param Category $obQuery
     * @param $sData
     * @return Category
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
            'id'            => $this->id,
            'name'          => $this->name,
            'slug'          => $this->slug,
            'code'          => $this->code,
            'preview_text'  => $this->preview_text,
            'description'   => $this->description,
            'nest_depth'    => $this->getDepth(),
            'parent_id'     => $this->parent_id,
            'preview_image' => $this->getFileData('preview_image'),
            'images'        => $this->getFileListData('images'),
        ];

        $arResult['children_list'] = $this->children()
            ->active()
            ->orderBy('nest_left', 'asc')
            ->lists('id');

        self::extendMethodResult(__FUNCTION__, $arResult, [$this]);
        return $arResult;
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

        //Add data children categories
        $arResult['children'] = [];
        if(!empty($arResult['children_list'])) {
            foreach($arResult['children_list'] as $iChildCategoryID) {

                $arChildCategoryData = self::getCacheData($iChildCategoryID);
                if(empty($arChildCategoryData)) {
                    continue;
                }

                $arResult['children'][$iChildCategoryID] = $arChildCategoryData;
            }
        }

        self::extendMethodResult(__FUNCTION__, $arResult);
        return $arResult;
    }
}