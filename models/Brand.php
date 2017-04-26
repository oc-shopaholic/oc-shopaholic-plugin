<?php namespace Lovata\Shopaholic\Models;

use Event;
use Model;

use Kharanenka\Helper\CustomValidationMessage;
use Kharanenka\Helper\DataFileModel;
use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Helper\CCache;
use Kharanenka\Scope\SlugField;

use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\Sortable;

use Lovata\Shopaholic\Plugin;
use Lovata\Toolbox\Plugin as ToolboxPlugin;
use Lovata\Toolbox\Traits\Helpers\TraitClassExtension;

/**
 * Class Brand
 * @package Lovata\Shopaholic\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
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
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * Relations
 * @property \System\Models\File $preview_image
 * @property \October\Rain\Database\Collection|\System\Models\File[] $images
 *
 * @property \October\Rain\Database\Collection|Product[] $products
 * @method $this|\October\Rain\Database\Relations\HasMany products()
 *
 */
class Brand extends Model
{
    use Validation;
    use Sortable;
    use ActiveField;
    use NameField;
    use CodeField;
    use SlugField;
    use ExternalIDField;
    use CustomValidationMessage;
    use DataFileModel;
    use TraitClassExtension;

    const CACHE_TAG_ELEMENT = 'shopaholic-brand-element';
    const CACHE_TAG_LIST = 'shopaholic-brand-list';

    public $table = 'lovata_shopaholic_brands';
    
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_brands',
    ];
    
    public $customMessages = [];
    public $attributeNames = [];

    public $attachOne = ['preview_image' => 'System\Models\File'];
    public $attachMany = ['images' => 'System\Models\File'];
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

    /**
     * Brand constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setCustomMessage(ToolboxPlugin::NAME, ['required', 'unique']);
        $this->setCustomAttributeName(ToolboxPlugin::NAME, ['name', 'slug']);
        parent::__construct($attributes);
    }

    /**
     * After save method
     */
    public function afterSave()
    {
        $this->clearCache();
        Event::fire(self::CACHE_TAG_ELEMENT.'.after.save', [$this]);
    }

    /**
     * After delete method
     */
    public function afterDelete()
    {
        $this->clearCache();
        Event::fire(self::CACHE_TAG_ELEMENT.'.after.delete', [$this]);
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
     * Get brand data
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
            'preview_image' => $this->getFileData('preview_image'),
            'images'        => $this->getFileListData('images'),
        ];

        self::extendMethodResult(__FUNCTION__, $arResult, [$this]);
        return $arResult;
    }

    /**
     * Get cached brand data
     * @param int $iElementID
     * @param null|Brand $obElement
     * @return array|null
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

            //Get element object
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

        self::extendMethodResult(__FUNCTION__, $arResult);
        return $arResult;
    }
}