<?php namespace Lovata\Shopaholic\Models;

use Model;

use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;

use October\Rain\Database\Traits\SoftDelete;
use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\Sortable;

use Lovata\Toolbox\Classes\Helper\PriceHelper;
use Lovata\Toolbox\Traits\Helpers\TraitCached;

/**
 * Class Tax
 * @package Lovata\Shopaholic\Models
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property                                                                      $id
 * @property bool                                                                 $active
 * @property bool                                                                 $is_global
 * @property string                                                               $name
 * @property string                                                               $description
 * @property float                                                                $percent
 * @property int                                                                  $sort_order
 * @property \October\Rain\Argon\Argon                                            $created_at
 * @property \October\Rain\Argon\Argon                                            $updated_at
 * @property \October\Rain\Argon\Argon                                            $deleted_at
 *
 * @property \October\Rain\Database\Collection|Category[]                         $category
 * @method static \October\Rain\Database\Relations\BelongsToMany|Category category()
 * @property \October\Rain\Database\Collection|Product[]                          $product
 * @method static \October\Rain\Database\Relations\BelongsToMany|Product product()
 * @property \October\Rain\Database\Collection|\RainLab\Location\Models\Country[] $country
 * @method static \October\Rain\Database\Relations\BelongsToMany|\RainLab\Location\Models\Country country()
 * @property \October\Rain\Database\Collection|\RainLab\Location\Models\State[]   $state
 * @method static \October\Rain\Database\Relations\BelongsToMany|\RainLab\Location\Models\State state()
 *
 * Orders for Shopaholic
 * @property bool                                                                 $applied_to_shipping_price
 */
class Tax extends Model
{
    use Validation;
    use SoftDelete;
    use Sortable;
    use ActiveField;
    use NameField;
    use CodeField;
    use ExternalIDField;
    use TraitCached;

    public $table = 'lovata_shopaholic_taxes';

    public $rules = [
        'name'    => 'required',
        'percent' => 'required',
    ];

    public $attributeNames = [
        'name'    => 'lovata.toolbox::lang.field.name',
        'percent' => 'lovata.shopaholic::lang.field.tax_percent',
    ];

    public $attachOne = [];
    public $attachMany = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [
        'category' => [
            Category::class,
            'table' => 'lovata_shopaholic_tax_category_link',
        ],
        'product'  => [
            Product::class,
            'table' => 'lovata_shopaholic_tax_product_link',
        ],
        'country'  => [
            'RainLab\Location\Models\Country',
            'table' => 'lovata_shopaholic_tax_country_link',
        ],
        'state'    => [
            'RainLab\Location\Models\State',
            'table' => 'lovata_shopaholic_tax_state_link',
        ],
    ];
    public $morphMany = [];

    public $dates = ['created_at', 'updated_at', 'deleted_at'];

    public $appends = [];
    public $purgeable = [];

    public $fillable = [
        'active',
        'is_global',
        'name',
        'description',
        'percent',
        'sort_order',
    ];

    public $cached = [
        'id',
        'is_global',
        'name',
        'description',
        'percent',
    ];

    public $visible = [];
    public $hidden = [];

    /**
     * Set percent attribute
     * @param string $sValue
     */
    protected function setPercentAttribute($sValue)
    {
        $this->attributes['percent'] = PriceHelper::toFloat($sValue);
    }
}
