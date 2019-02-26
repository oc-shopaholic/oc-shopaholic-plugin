<?php namespace Lovata\Shopaholic\Models;

use Model;

use October\Rain\Database\Traits\Validation;

use Lovata\Toolbox\Classes\Helper\PriceHelper;
use Lovata\Toolbox\Traits\Helpers\PriceHelperTrait;

/**
 * Class Price
 * @package Lovata\Shopaholic\Models
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property                                     $id
 * @property int                                 $item_id
 * @property string                              $item_type
 * @property string                              $price
 * @property float                               $price_value
 * @property string                              $discount_price
 * @property float                               $discount_price_value
 * @property string                              $old_price
 * @property float                               $old_price_value
 * @property int                                 $price_type_id
 * @property \October\Rain\Argon\Argon           $created_at
 * @property \October\Rain\Argon\Argon           $updated_at
 *
 * @property \Lovata\Shopaholic\Models\PriceType $price_type
 * @method \October\Rain\Database\Relations\BelongsTo|PriceType price_type()
 *
 * @method static $this getByItemID(int $iItemID)
 * @method static $this getByItemType(string $sItemType)
 * @method static $this getByPriceType(int $iPriceTypeID)
 */
class Price extends Model
{
    use Validation;
    use PriceHelperTrait;

    public $table = 'lovata_shopaholic_prices';

    public $rules = [
        'item_id'   => 'required',
        'item_type' => 'required',
    ];

    public $attributeNames = [];

    public $attachOne = [];
    public $attachMany = [];
    public $hasMany = [];
    public $belongsTo = [
        'price_type' => [PriceType::class],
    ];
    public $belongsToMany = [];
    public $morphTo = [
        'item' => [],
    ];
    public $morphMany = [];

    public $dates = ['created_at', 'updated_at'];

    public $appends = [];
    public $purgeable = [];

    public $arPriceField = ['price', 'old_price', 'discount_price'];

    public $fillable = [
        'item_id',
        'item_type',
        'price',
        'discount_price',
        'old_price',
        'price_type_id',
    ];

    public $visible = [];
    public $hidden = [];

    /**
     * Get by item id
     * @param Offer $obQuery
     * @param int   $sData
     *
     * @return Offer
     */
    public function scopeGetByItemID($obQuery, $sData)
    {
        if (!empty($sData)) {
            $obQuery->where('item_id', $sData);
        }

        return $obQuery;
    }

    /**
     * Get by item type
     * @param Offer $obQuery
     * @param int   $sData
     *
     * @return Offer
     */
    public function scopeGetByItemType($obQuery, $sData)
    {
        if (!empty($sData)) {
            $obQuery->where('item_type', $sData);
        }

        return $obQuery;
    }

    /**
     * Get by price type ID
     * @param Offer $obQuery
     * @param int   $sData
     *
     * @return Offer
     */
    public function scopeGetByPriceType($obQuery, $sData)
    {
        if (!empty($sData)) {
            $obQuery->where('price_type_id', $sData);
        } else {
            $obQuery->whereNull('price_type_id');
        }

        return $obQuery;
    }

    /**
     * Get discount_price_value attribute
     * @return float
     */
    protected function getDiscountPriceValueAttribute()
    {
        $fPrice = 0;
        if ($this->old_price_value > 0) {
            $fPrice = PriceHelper::round($this->old_price_value - $this->price_value);
        }

        return $fPrice;
    }
}
