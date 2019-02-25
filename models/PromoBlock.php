<?php namespace Lovata\Shopaholic\Models;

use Lang;
use Model;
use Event;

use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\NameField;
use Kharanenka\Scope\SlugField;
use Kharanenka\Scope\HiddenField;

use October\Rain\Database\Traits\Sluggable;
use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\Sortable;

use Lovata\Toolbox\Traits\Helpers\TraitCached;

/**
 * Class PromoBlock
 * @package Lovata\Shopaholic\Models
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property                                                                                 $id
 * @property bool                                                                            $active
 * @property string                                                                          $name
 * @property string                                                                          $slug
 * @property string                                                                          $code
 * @property string                                                                          $type
 * @property string                                                                          $preview_text
 * @property string                                                                          $description
 * @property int                                                                             $sort_order
 * @property \October\Rain\Argon\Argon                                                       $date_begin
 * @property \October\Rain\Argon\Argon                                                       $date_end
 * @property \October\Rain\Argon\Argon                                                       $created_at
 * @property \October\Rain\Argon\Argon                                                       $updated_at
 *
 * Relations
 * @property \System\Models\File                                                             $preview_image
 * @property \October\Rain\Database\Collection|\System\Models\File[]                         $images
 *
 * @property \October\Rain\Database\Collection|\Lovata\Shopaholic\Models\Product[]           $product
 * @method static \October\Rain\Database\Relations\BelongsToMany|Product product()
 *
 * Discounts for Shopaholic
 * @property \October\Rain\Database\Collection|\Lovata\DiscountsShopaholic\Models\Discount[] $discount
 * @method static \October\Rain\Database\Relations\BelongsToMany|\Lovata\DiscountsShopaholic\Models\Discount discount()
 *
 * Campaign for Shopaholic
 * @property \October\Rain\Database\Collection|\Lovata\CampaignsShopaholic\Models\Campaign[] $campaign
 * @method static \October\Rain\Database\Relations\BelongsToMany|\Lovata\CampaignsShopaholic\Models\Campaign campaign()
 *
 * Coupons for Shopaholic
 * @property \October\Rain\Database\Collection|\Lovata\CouponsShopaholic\Models\CouponGroup[] $coupon_group
 * @method static \October\Rain\Database\Relations\BelongsToMany|\Lovata\CouponsShopaholic\Models\CouponGroup coupon_group()
 *
 * @method static $this active()
 * @method static $this hidden()
 * @method static $this notHidden()
 */
class PromoBlock extends Model
{
    use Validation;
    use Sortable;
    use Sluggable;
    use ActiveField;
    use NameField;
    use CodeField;
    use SlugField;
    use HiddenField;
    use TraitCached;

    const PROMO_BLOCK_TYPE = 'promo_block';

    const EVENT_GET_TYPE_LIST = 'shopaholic.promo_block.get.type.list';
    const EVENT_GET_PRODUCT_LIST = 'shopaholic.promo_block.get.product.list';

    public $table = 'lovata_shopaholic_promo_block';

    public $implement = [
        '@RainLab.Translate.Behaviors.TranslatableModel',
    ];

    public $translatable = ['name', 'preview_text', 'description'];

    public $rules = [
        'name'       => 'required',
        'type'       => 'required',
        'date_begin' => 'required',
        'slug'       => 'required|unique:lovata_shopaholic_promo_block',
    ];

    public $attributeNames = [
        'name'       => 'lovata.toolbox::lang.field.name',
        'type'       => 'lovata.toolbox::lang.field.type',
        'slug'       => 'lovata.toolbox::lang.field.slug',
        'date_begin' => 'lovata.toolbox::lang.field.date_begin',
    ];

    public $slugs = ['slug' => 'name'];

    public $attachOne = ['preview_image' => 'System\Models\File'];
    public $attachMany = ['images' => 'System\Models\File'];
    public $belongsTo = [];
    public $hasMany = [];
    public $belongsToMany = [
        'product' => [
            Product::class,
            'table' => 'lovata_shopaholic_promo_block_relation',
            'key'   => 'promo_id',
        ],
    ];
    public $morphMany = [];

    public $appends = [];
    public $purgeable = [];

    public $dates = ['created_at', 'updated_at', 'date_begin', 'date_end'];

    public $fillable = [
        'active',
        'name',
        'slug',
        'type',
        'code',
        'preview_text',
        'description',
        'sort_order',
        'date_begin',
        'date_end',
    ];

    public $cached = [
        'id',
        'name',
        'slug',
        'type',
        'code',
        'preview_text',
        'preview_image',
        'description',
        'images',
        'date_begin',
        'date_end',
    ];

    public $visible = [];
    public $hidden = [];

    /**
     * Fire event and get promo content type list
     * @return array
     */
    public static function getTypeList() : array
    {
        $arResult = [
            self::PROMO_BLOCK_TYPE => Lang::get('lovata.shopaholic::lang.field.promo_block_type'),
        ];

        $arEventDataList = Event::fire(self::EVENT_GET_TYPE_LIST);
        if (empty($arEventDataList)) {
            return $arResult;
        }

        foreach ($arEventDataList as $arEventData) {
            if (empty($arEventData) || !is_array($arEventData)) {
                continue;
            }

            $arResult = array_merge($arResult, $arEventData);
        }

        return $arResult;
    }

    /**
     * Get type list for backend fields
     * @return array
     */
    public function getTypeOptions() : array
    {
        return self::getTypeList();
    }
}
