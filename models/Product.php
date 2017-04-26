<?php namespace Lovata\Shopaholic\Models;

use Model;
use Event;

use Kharanenka\Helper\CustomValidationMessage;
use Kharanenka\Helper\DataFileModel;
use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CategoryBelongsTo;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Scope\SlugField;
use Kharanenka\Helper\CCache;

use Lovata\Shopaholic\Plugin;
use Lovata\Toolbox\Plugin as ToolboxPlugin;
use Lovata\Shopaholic\Classes\ProductListStore;
use Lovata\Toolbox\Traits\Helpers\TraitClassExtension;

use October\Rain\Database\Traits\SoftDelete;
use October\Rain\Database\Traits\Validation;

/**
 * Class Product
 * @package Lovata\Shopaholic\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * 
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
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
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * Relations
 * @property \System\Models\File $preview_image
 * @property \October\Rain\Database\Collection|\System\Models\File[] $images
 *
 * @property Category $category
 * @method static \October\Rain\Database\Relations\BelongsTo category()
 *
 * @property Brand $brand
 * @method static \October\Rain\Database\Relations\BelongsTo brand()
 *
 * @property \October\Rain\Database\Collection|Offer[] $offers
 * @method Offer|\October\Rain\Database\Relations\HasMany offers()
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
    use TraitClassExtension;

    const CACHE_TAG_ELEMENT = 'shopaholic-product-element';
    const CACHE_TAG_LIST = 'shopaholic-product-list';

    public $table = 'lovata_shopaholic_products';
  
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_products',
    ];

    public $customMessages = [];
    public $attributeNames = [];

    public $attachOne = ['preview_image' => 'System\Models\File'];
    public $attachMany = ['images' => 'System\Models\File'];
    public $hasMany = ['offers' => ['Lovata\Shopaholic\Models\Offer']];
    public $belongsTo = [
        'category' => ['Lovata\Shopaholic\Models\Category'],
        'brand'    => ['Lovata\Shopaholic\Models\Brand'],
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

        parent::__construct($attributes);
    }

    /**
     * After save method
     */
    public function afterSave()
    {
        $this->clearCache();

        ProductListStore::updateCacheAfterSave($this);
        Event::fire(self::CACHE_TAG_ELEMENT.'.after.save', [$this]);
    }

    /**
     * After delete method
     */
    public function afterDelete()
    {
        $this->clearCache();

        ProductListStore::updateCacheAfterDelete($this);
        Event::fire(self::CACHE_TAG_ELEMENT.'.after.delete', [$this]);
    }

    /**
     * Clear cache product data
     */
    public function clearCache()
    {
        CCache::clear([Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT], $this->id);
        Event::fire(self::CACHE_TAG_ELEMENT.'.cache.clear', [$this]);
    }

    /**
     * Get product data
     * @return array
     */
    public function getData()
    {
        $arResult = [
            'id'            => $this->id,
            'active'        => $this->active,
            'trashed'       => $this->trashed(),
            'name'          => $this->name,
            'slug'          => $this->slug,
            'code'          => $this->code,
            'category_id'   => $this->category_id,
            'brand_id'      => $this->brand_id,
            'preview_text'  => $this->preview_text,
            'preview_image' => $this->getFileData('preview_image'),
            'description'   => $this->description,
            'images'        => $this->getFileListData('images'),
            'offer_list'    => $this->offers->lists('id'),
        ];

        self::extendMethodResult(__FUNCTION__, $arResult, [$this]);
        return $arResult;
    }

    /**
     * Get cached product data
     * @param int $iElementID
     * @param Product $obElement
     * @param \October\Rain\Database\Collection $obSettings
     *      check_product_active    - true|false
     *      check_product_trashed   - true|false
     *      check_offer_active      - true|false
     *      check_offer_trashed     - true|false
     *
     * @return array|null
     */
    public static function getCacheData($iElementID, $obElement = null, $obSettings = null)
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
                $obElement = self::withTrashed()->find($iElementID);
            }

            if(empty($obElement)) {
                return null;
            }

            $arResult = $obElement->getData();

            //Set cache data
            CCache::forever($arCacheTags, $sCacheKey, $arResult);
        }

        if(!self::checkProductData($arResult, $obSettings)) {
            return null;
        }
        
        //Get offer cached data
        if(!empty($arResult['offer_list'])) {
            foreach($arResult['offer_list'] as $iOfferID) {
                
                $arOfferData = Offer::getCacheData($iOfferID, null, $obSettings);
                if(empty($arOfferData)) {
                    continue;
                }
                
                $arResult['offer'][$iOfferID] = $arOfferData;
            }
        }

        self::extendMethodResult(__FUNCTION__, $arResult);
        return $arResult;
    }

    /**
     * Check product data
     * @param array $arResult
     * @param \October\Rain\Database\Collection $obSettings
     * @return bool
     */
    protected static function checkProductData($arResult, $obSettings)
    {
        if(empty($obSettings)) {
            return $arResult['active'] && !$arResult['trashed'];
        }

        if($obSettings->get('check_product_active') && !$arResult['active']) {
            return false;
        }

        if($obSettings->get('check_product_trashed') && $arResult['trashed']) {
            return false;
        }

        return true;
    }
}