<?php namespace Lovata\Shopaholic\Models;

use Model;

use Kharanenka\Helper\DataFileModel;
use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;

use Lovata\Shopaholic\Classes\Helper\PriceHelper;

use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\SoftDelete;

/**
 * Class Offer
 * @package Lovata\Shopaholic\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * 
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 * 
 * @property $id
 * @property bool $active
 * @property string $name
 * @property string $code
 * @property string $external_id
 * @property string $preview_text
 * @property string $description
 * @property double $price
 * @property double $old_price
 * @property integer $quantity
 * @property int $product_id
 * @property \October\Rain\Argon\Argon $created_at
 * @property \October\Rain\Argon\Argon $updated_at
 * @property \October\Rain\Argon\Argon $deleted_at
 *
 * Relations
 * @property \System\Models\File $preview_image
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
 * @see \Lovata\PropertiesShopaholic\Classes\Event\OfferModelHandler::addPropertyMethods
 * @property array $property
 */
class Offer extends Model
{
    use Validation;
    use SoftDelete;
    use ActiveField;
    use NameField;
    use CodeField;
    use ExternalIDField;
    use DataFileModel;

    public $table = 'lovata_shopaholic_offers';

    public $rules = ['name' => 'required'];

    public $attributeNames = [
        'lovata.toolbox::lang.field.name',
    ];
    
    public $attachOne = ['preview_image' => 'System\Models\File'];
    public $attachMany = ['images' => 'System\Models\File'];
    public $belongsTo = ['product' => [Product::class]];

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

    public $dates = ['created_at', 'updated_at', 'deleted_at'];
    public $appends = [];
    public $casts = [];

    /**
     * Get price value
     * @return double
     */
    public function getPriceValue()
    {
        return $this->attributes['price'];
    }

    /**
     * Get price value
     * @return double
     */
    public function getOldPriceValue()
    {
        return $this->attributes['old_price'];
    }
    
    /**
     * Accessor for price custom format
     *
     * @param  string  $dPrice
     * @return string
     */
    public function getPriceAttribute($dPrice)
    {
        /** @var PriceHelper $obPriceHelper */
        $obPriceHelper = app()->make(PriceHelper::class);
        return $obPriceHelper->get($dPrice);
    }

    /**
     * Accessor for discount price custom format
     *
     * @param  string  $dPrice
     * @return string
     */
    public function getOldPriceAttribute($dPrice)
    {
        /** @var PriceHelper $obPriceHelper */
        $obPriceHelper = app()->make(PriceHelper::class);
        return $obPriceHelper->get($dPrice);
    }

    /**
     * Format price to decimal format
     * @param  string  $sPrice
     */
    public function setPriceAttribute($sPrice)
    {
        $sPrice = str_replace(',', '.', $sPrice);
        $sPrice = (float) preg_replace("/[^0-9\.]/", "",$sPrice);
        $this->attributes['price'] = $sPrice;
    }

    /**
     * Format discount price to decimal format
     * @param  string  $sPrice
     */
    public function setOldPriceAttribute($sPrice)
    {
        $sPrice = str_replace(',', '.', $sPrice);
        $sPrice = (float) preg_replace("/[^0-9\.]/", "",$sPrice);
        if($sPrice <= $this->getPriceValue()) {
            $sPrice = 0;
        }
        
        $this->attributes['old_price'] = $sPrice;
    }

    /**
     * Get element by product ID
     * @param Offer $obQuery
     * @param $sData
     * @return Offer
     */
    public function scopeGetByProduct($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->where('product_id', $sData);
        }
        return $obQuery;
    }

    /**
     * Get by quantity
     * @param Offer $obQuery
     * @param $sData
     * @param string $sCondition
     * @return Offer
     */
    public function scopeGetByQuantity($obQuery, $sData, $sCondition = '=')
    {
        if(empty($sData)) {
            $sData = 0;
        }

        if(!empty($sCondition)) {
            $obQuery->where('quantity', $sCondition, $sData);
        }

        return $obQuery;
    }

    /**
     * Get by price
     * @param Offer $obQuery
     * @param $sData
     * @param string $sCondition
     * @return Offer
     */
    public function scopeGetByPrice($obQuery, $sData, $sCondition = '=')
    {
        if(empty($sData)) {
            $sData = 0;
        }

        if(!empty($sCondition)) {
            $obQuery->where('price', $sCondition, $sData);
        }

        return $obQuery;
    }

    /**
     * Get by old price
     * @param Offer $obQuery
     * @param $sData
     * @param string $sCondition
     * @return Offer
     */
    public function scopeGetByOldPrice($obQuery, $sData, $sCondition = '=')
    {
        if(empty($sData)) {
            $sData = 0;
        }

        if(!empty($sCondition)) {
            $obQuery->where('old_price', $sCondition, $sData);
        }

        return $obQuery;
    }
}