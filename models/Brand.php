<?php namespace Lovata\Shopaholic\Models;

use Backend\Models\ImportModel;

use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Scope\SlugField;

use October\Rain\Database\Traits\Sluggable;
use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\Sortable;

use Lovata\Toolbox\Traits\Helpers\TraitCached;
use Lovata\Shopaholic\Classes\Import\ImportBrandModelFromCSV;

/**
 * Class Brand
 * @package Lovata\Shopaholic\Models
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property                                                                                  $id
 * @property bool                                                                             $active
 * @property string                                                                           $name
 * @property string                                                                           $slug
 * @property string                                                                           $code
 * @property string                                                                           $external_id
 * @property string                                                                           $preview_text
 * @property string                                                                           $description
 * @property int                                                                              $sort_order
 * @property \October\Rain\Argon\Argon                                                        $created_at
 * @property \October\Rain\Argon\Argon                                                        $updated_at
 *
 * Relations
 * @property \System\Models\File                                                              $preview_image
 * @property \System\Models\File                                                              $icon
 * @property \October\Rain\Database\Collection|\System\Models\File[]                          $images
 *
 * @property \October\Rain\Database\Collection|Product[]                                      $product
 * @method \October\Rain\Database\Relations\HasMany|Product product()
 *
 * Search for Shopaholic, Sphinx for Shopaholic
 * @property string                                                                           $search_synonym
 * @property string                                                                           $search_content
 *
 * Discounts for Shopaholic
 * @property \October\Rain\Database\Collection|\Lovata\DiscountsShopaholic\Models\Discount[]  $discount
 * @method static \October\Rain\Database\Relations\BelongsToMany|\Lovata\DiscountsShopaholic\Models\Discount discount()
 *
 * Coupons for Shopaholic
 * @property \October\Rain\Database\Collection|\Lovata\CouponsShopaholic\Models\CouponGroup[] $coupon_group
 * @method static \October\Rain\Database\Relations\BelongsToMany|\Lovata\CouponsShopaholic\Models\CouponGroup coupon_group()
 *
 * Campaign for Shopaholic
 * @property \October\Rain\Database\Collection|\Lovata\CampaignsShopaholic\Models\Campaign[]  $campaign
 * @method static \October\Rain\Database\Relations\BelongsToMany|\Lovata\CampaignsShopaholic\Models\Campaign campaign()
 */
class Brand extends ImportModel
{
    use Validation;
    use Sortable;
    use Sluggable;
    use ActiveField;
    use NameField;
    use CodeField;
    use SlugField;
    use ExternalIDField;
    use TraitCached;

    public $table = 'lovata_shopaholic_brands';

    public $implement = [
        '@RainLab.Translate.Behaviors.TranslatableModel',
    ];

    public $translatable = ['name', 'preview_text', 'description'];

    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_brands',
    ];

    public $attributeNames = [
        'name' => 'lovata.toolbox::lang.field.name',
        'slug' => 'lovata.toolbox::lang.field.slug',
    ];

    public $slugs = ['slug' => 'name'];

    public $attachOne = [
        'preview_image' => 'System\Models\File',
        'icon'          => 'System\Models\File',
        'import_file'   => [\System\Models\File::class, 'public' => false],
    ];
    public $attachMany = ['images' => 'System\Models\File'];
    public $hasMany = ['product' => Product::class];
    public $belongsToMany = [];
    public $morphMany = [];
    public $belongsTo = [];

    public $dates = ['created_at', 'updated_at'];

    public $appends = [];
    public $purgeable = [];

    public $fillable = [
        'active',
        'name',
        'slug',
        'code',
        'external_id',
        'preview_text',
        'description',
    ];

    public $cached = [
        'id',
        'name',
        'slug',
        'code',
        'preview_text',
        'preview_image',
        'icon',
        'description',
        'images',
    ];

    public $visible = [];
    public $hidden = [];

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

        $obImport = new ImportBrandModelFromCSV();
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
