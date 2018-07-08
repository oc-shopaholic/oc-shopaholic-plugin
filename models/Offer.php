<?php namespace Lovata\Shopaholic\Models;

use Model;

use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;

use Lovata\Toolbox\Traits\Helpers\TraitCached;
use Lovata\Toolbox\Traits\Helpers\PriceHelperTrait;

use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\SoftDelete;
use October\Rain\Database\Traits\Purgeable;

/**
 * Class Offer
 * @package Lovata\Shopaholic\Models
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property                                   $id
 * @property bool                              $active
 * @property string                            $name
 * @property string                            $code
 * @property string                            $external_id
 * @property string                            $preview_text
 * @property string                            $description
 * @property string                            $price
 * @property float                             $price_value
 * @property string                            $old_price
 * @property float                             $old_price_value
 * @property integer                           $quantity
 * @property int                               $product_id
 * @property \October\Rain\Argon\Argon         $created_at
 * @property \October\Rain\Argon\Argon         $updated_at
 * @property \October\Rain\Argon\Argon         $deleted_at
 *
 * Relations
 * @property \System\Models\File               $preview_image
 * @property \October\Rain\Database\Collection $images
 *
 * @property \Lovata\Shopaholic\Models\Product $product
 * @method \October\Rain\Database\Relations\BelongsTo|Product product()
 *
 * @method static $this getByProduct(int $iProductID)
 * @method static $this getByQuantity(int $iCount, string $sCondition = '=')
 * @method static $this getByPrice(int $iPrice, string $sCondition = '=')
 * @method static $this getByOldPrice(int $iPrice, string $sCondition = '=')
 *
 * Properties for Shopaholic
 * @see     \Lovata\PropertiesShopaholic\Classes\Event\OfferModelHandler::addPropertyMethods
 * @property array                             $property
 *
 * @property \October\Rain\Database\Collection|\Lovata\PropertiesShopaholic\Models\PropertyValueLink[] $property_value
 * @method static \October\Rain\Database\Relations\MorphMany|\Lovata\PropertiesShopaholic\Models\PropertyValueLink property_value()
 */
class Offer extends Model
{
    use Validation;
    use SoftDelete;
    use Purgeable;
    use ActiveField;
    use NameField;
    use CodeField;
    use ExternalIDField;
    use PriceHelperTrait;
    use TraitCached;

    public $table = 'lovata_shopaholic_offers';

    public $implement = [
        '@RainLab.Translate.Behaviors.TranslatableModel',
    ];

    public $translatable = ['name', 'preview_text', 'description'];

    public $rules = ['name' => 'required'];

    public $attributeNames = [
        'name' => 'lovata.toolbox::lang.field.name',
    ];

    public $attachOne = ['preview_image' => 'System\Models\File'];
    public $attachMany = ['images' => 'System\Models\File'];
    public $belongsTo = ['product' => [Product::class]];
    public $morphMany = [];

    public $fillable = [
        'active',
        'name',
        'code',
        'product_id',
        'external_id',
        'price',
        'old_price',
        'quantity',
        'preview_text',
        'description',
    ];

    public $cached = [
        'id',
        'active',
        'product_id',
        'name',
        'code',
        'preview_text',
        'preview_image',
        'description',
        'images',
        'price_value',
        'old_price_value',
        'quantity',
    ];

    public $dates = ['created_at', 'updated_at', 'deleted_at'];
    public $appends = [];
    public $purgeable = [];
    public $casts = [];

    public $arPriceField = ['price', 'old_price'];

    /**
     * Set quantity attribute value
     * @param  int $iQuantity
     */
    public function setQuantityAttribute($iQuantity)
    {
        $iQuantity = (int) $iQuantity;
        if (empty($iQuantity) || $iQuantity < 0) {
            $iQuantity = 0;
        }

        $this->attributes['quantity'] = $iQuantity;
    }

    /**
     * Get element by product ID
     * @param Offer  $obQuery
     * @param string $sData
     *
     * @return Offer
     */
    public function scopeGetByProduct($obQuery, $sData)
    {
        if (!empty($sData)) {
            $obQuery->where('product_id', $sData);
        }

        return $obQuery;
    }

    /**
     * Get by quantity
     * @param Offer  $obQuery
     * @param string $sData
     * @param string $sCondition
     *
     * @return Offer
     */
    public function scopeGetByQuantity($obQuery, $sData, $sCondition = '=')
    {
        if (empty($sData)) {
            $sData = 0;
        }

        if (!empty($sCondition)) {
            $obQuery->where('quantity', $sCondition, $sData);
        }

        return $obQuery;
    }
}
