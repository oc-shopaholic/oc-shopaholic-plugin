<?php namespace Lovata\Shopaholic\Models;

use Model;

use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;

use October\Rain\Database\Traits\SoftDelete;
use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\Sortable;

use Lovata\Toolbox\Traits\Helpers\TraitCached;

/**
 * Class PriceType
 * @package Lovata\Shopaholic\Models
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property                           $id
 * @property bool                      $active
 * @property string                    $name
 * @property string                    $code
 * @property string                    $external_id
 * @property int                       $sort_order
 * @property int                       $currency_id
 * @property \October\Rain\Argon\Argon $created_at
 * @property \October\Rain\Argon\Argon $updated_at
 * @property \October\Rain\Argon\Argon $deleted_at
 *
 * @property \Lovata\Shopaholic\Models\Currency $currency
 * @method \October\Rain\Database\Relations\BelongsTo|Currency currency()
 */
class PriceType extends Model
{
    use Validation;
    use SoftDelete;
    use Sortable;
    use ActiveField;
    use NameField;
    use CodeField;
    use ExternalIDField;
    use TraitCached;

    public $table = 'lovata_shopaholic_price_types';

    public $rules = [
        'name' => 'required',
        'code' => 'required|unique:lovata_shopaholic_price_types',
    ];

    public $attributeNames = [
        'name' => 'lovata.toolbox::lang.field.name',
        'code' => 'lovata.toolbox::lang.field.code',
    ];

    public $attachOne = [];
    public $attachMany = [];
    public $hasMany = [];
    public $belongsTo = [
        'currency' => [Currency::class],
    ];
    public $belongsToMany = [];
    public $morphMany = [];

    public $dates = ['created_at', 'updated_at', 'deleted_at'];

    public $appends = [];
    public $purgeable = [];

    public $fillable = [
        'active',
        'name',
        'code',
        'external_id',
        'sort_order',
    ];

    public $cached = [
        'id',
        'name',
        'code',
    ];

    public $visible = [];
    public $hidden = [];
}
