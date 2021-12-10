<?php namespace Lovata\Shopaholic\Models;

use Backend\Models\ImportModel;

use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CategoryBelongsTo;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Scope\SlugField;

use October\Rain\Database\Traits\Sluggable;
use October\Rain\Database\Traits\SoftDelete;
use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\Purgeable;
use October\Rain\Database\Traits\Nullable;

use Lovata\Toolbox\Traits\Helpers\TraitCached;
use Lovata\Shopaholic\Classes\Import\ImportProductModelFromCSV;

/**
 * Class Product
 * @package Lovata\Shopaholic\Models
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property                                                                                           $id
 * @property bool                                                                                      $active
 * @property string                                                                                    $name
 * @property string                                                                                    $slug
 * @property string                                                                                    $code
 * @property int                                                                                       $category_id
 * @property int                                                                                       $brand_id
 * @property string                                                                                    $external_id
 * @property string                                                                                    $preview_text
 * @property string                                                                                    $description
 * @property \October\Rain\Argon\Argon                                                                 $created_at
 * @property \October\Rain\Argon\Argon                                                                 $updated_at
 * @property \October\Rain\Argon\Argon                                                                 $deleted_at
 *
 * Relations
 * @property \System\Models\File                                                                       $preview_image
 * @property \October\Rain\Database\Collection|\System\Models\File[]                                   $images
 *
 * @property Category                                                                                  $category
 * @method static \October\Rain\Database\Relations\BelongsTo|Category category()
 *
 * @property \October\Rain\Database\Collection|Category[]                                              $additional_category
 * @method static \October\Rain\Database\Relations\BelongsToMany|Category additional_category()
 *
 * @property Brand                                                                                     $brand
 * @method static \October\Rain\Database\Relations\BelongsTo|Brand brand()
 *
 * @property \October\Rain\Database\Collection|Offer[]                                                 $offer
 * @method \October\Rain\Database\Relations\HasMany|Offer offer()
 *
 * @method static $this getByBrand(int $iBrandID)
 *
 * Properties for Shopaholic
 * @see     \Lovata\PropertiesShopaholic\Classes\Event\Product\ProductModelHandler::addPropertyMethods
 * @property array                                                                                     $property
 *
 * @property \October\Rain\Database\Collection|\Lovata\PropertiesShopaholic\Models\PropertyValueLink[] $property_value
 * @method static \October\Rain\Database\Relations\MorphMany|\Lovata\PropertiesShopaholic\Models\PropertyValueLink property_value()
 *
 * Popularity for Shopaholic
 * @property int                                                                                       $popularity
 *
 * Reviews for Shopaholic
 * @property float                                                                                     $rating
 * @property array                                                                                     $rating_data
 * @property \October\Rain\Database\Collection|\Lovata\ReviewsShopaholic\Models\Review[]               $review
 * @method static \October\Rain\Database\Relations\HasMany|\Lovata\ReviewsShopaholic\Models\Review review()
 *
 * Related products for Shopaholic
 * @property \October\Rain\Database\Collection|Product[]                                               $related
 * @method static \October\Rain\Database\Relations\BelongsToMany|$this related()
 *
 * Accessories for Shopaholic
 * @property \October\Rain\Database\Collection|Product[]                                               $accessory
 * @method static \October\Rain\Database\Relations\BelongsToMany|$this accessory()
 *
 * Search for Shopaholic, Sphinx for Shopaholic
 * @property string                                                                                    $search_synonym
 * @property string                                                                                    $search_content
 *
 * Discounts for Shopaholic
 * @property \October\Rain\Database\Collection|\Lovata\DiscountsShopaholic\Models\Discount[]           $discount
 * @method static \October\Rain\Database\Relations\BelongsToMany|\Lovata\DiscountsShopaholic\Models\Discount discount()
 *
 * Coupons for Shopaholic
 * @property \October\Rain\Database\Collection|\Lovata\CouponsShopaholic\Models\CouponGroup[]          $coupon_group
 * @method static \October\Rain\Database\Relations\BelongsToMany|\Lovata\CouponsShopaholic\Models\CouponGroup coupon_group()
 *
 * Campaign for Shopaholic
 * @property \October\Rain\Database\Collection|\Lovata\CampaignsShopaholic\Models\Campaign[]           $campaign
 * @method static \October\Rain\Database\Relations\BelongsToMany|\Lovata\CampaignsShopaholic\Models\Campaign campaign()
 *
 * Labels for Shopaholic
 * @property \October\Rain\Database\Collection|\Lovata\LabelsShopaholic\Models\Label[]                 $label
 * @method static \October\Rain\Database\Relations\BelongsToMany|\Lovata\LabelsShopaholic\Models\Label label()
 *
 * Subscriptions for Shopaholic
 * @property bool                                                                                      $is_subscription
 *
 * YandexMarket for Shopaholic
 * @property \System\Models\File                                                                       $preview_image_yandex
 * @property \October\Rain\Database\Collection|\System\Models\File[]                                   $images_yandex
 *
 * Facebook for Shopaholic
 * @property \System\Models\File                                                                       $preview_image_facebook
 * @property \October\Rain\Database\Collection|\System\Models\File[]                                   $images_facebook
 *
 * VKontakte for Shopaholic
 * @property bool                                                                                      $active_vk
 * @property int                                                                                       $external_vk_id
 * @property \System\Models\File                                                                       $preview_image_vkontakte
 * @property \October\Rain\Database\Collection|\System\Models\File[]                                   $images_vkontakte
 * @method static $this activeVK()
 * @method static $this notActiveVK()
 * @method static $this isNotEmptyExternalVkId()
 *
 * Downloadable file for Shopaholic
 * @property bool                                                                                      $is_file_access
 */
class Product extends ImportModel
{
    use Validation;
    use SoftDelete;
    use Sluggable;
    use Purgeable;
    use ActiveField;
    use NameField;
    use CategoryBelongsTo;
    use SlugField;
    use CodeField;
    use ExternalIDField;
    use TraitCached;
    use Nullable;


    public $table = 'lovata_shopaholic_products';

    public $implement = [
        '@RainLab.Translate.Behaviors.TranslatableModel',
    ];

    public $translatable = ['name', 'preview_text', 'description'];

    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_products',
    ];

    public $attributeNames = [
        'name' => 'lovata.toolbox::lang.field.name',
        'slug' => 'lovata.toolbox::lang.field.slug',
    ];

    public $slugs = ['slug' => 'name'];

    public $attachOne = [
        'preview_image' => 'System\Models\File',
        'import_file'   => [\System\Models\File::class, 'public' => false],
    ];
    public $attachMany = ['images' => 'System\Models\File'];
    public $hasMany = ['offer' => [Offer::class]];
    public $belongsTo = [
        'category' => [Category::class],
        'brand'    => [Brand::class],
    ];
    public $belongsToMany = [
        'additional_category' => [
            Category::class,
            'table' => 'lovata_shopaholic_additional_categories',
        ],
        'promo_block'         => [
            PromoBlock::class,
            'table'    => 'lovata_shopaholic_promo_block_relation',
            'otherKey' => 'promo_id',
        ],
    ];

    public $morphMany = [];

    public $appends = [];
    public $purgeable = [];
    public $nullable = [];
    public $fillable = [
        'active',
        'name',
        'slug',
        'code',
        'external_id',
        'preview_text',
        'description',
        'brand_id',
        'category_id',
    ];

    public $cached = [
        'id',
        'active',
        'name',
        'slug',
        'code',
        'category_id',
        'brand_id',
        'preview_text',
        'preview_image',
        'description',
        'images',
    ];

    public $dates = ['created_at', 'updated_at', 'deleted_at'];

    public $jsonable = [];

    public $visible = [];
    public $hidden = [];

    /**
     * Get element by brand ID
     * @param Product $obQuery
     * @param string  $sData
     * @return $this
     */
    public function scopeGetByBrand($obQuery, $sData)
    {
        if (!empty($sData)) {
            $obQuery->where('brand_id', $sData);
        }

        return $obQuery;
    }

    /**
     * Get element by categories
     * @param Product $obQuery
     * @param string  $sData
     * @return $this
     */
    public function scopeGetByCategories($obQuery, $sData)
    {
        if (!empty($sData)) {
            foreach ($sData as $category) {
                $obQuery->orWhere('category_id', $category)->orWhereHas('additional_category', function ($obQuery) use ($category) {
                    $obQuery->where('category_id', $category);
                });
            }
        }

        return $obQuery;
    }

    /**
     * Before validate event handler
     */
    public function beforeValidate()
    {
        if (empty($this->slug)) {
            $this->slugAttributes();
        }
    }

    /**
     * Import item list from CSV file
     * @param array $arElementList
     * @param null  $sSessionKey
     * @throws \Throwable
     */
    public function importData($arElementList, $sSessionKey = null)
    {
        if (empty($arElementList)) {
            return;
        }

        $obImport = new ImportProductModelFromCSV();
        $obImport->setDeactivateFlag();

        foreach ($arElementList as $iKey => $arImportData) {
            $obImport->import($arImportData);
            $sResultMethod = $obImport->getResultMethod();
            if (in_array($sResultMethod, ['logUpdated', 'logCreated'])) {
                $this->$sResultMethod();
            } else {
                $sErrorMessage = $obImport->getResultError();
                $this->$sResultMethod($iKey, $sErrorMessage);
            }
        }

        $obImport->deactivateElements();
    }
}
