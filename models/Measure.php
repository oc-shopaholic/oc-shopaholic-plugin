<?php namespace Lovata\Shopaholic\Models;

use Model;
use Kharanenka\Scope\NameField;
use October\Rain\Database\Traits\Validation;
use Lovata\Toolbox\Traits\Helpers\TraitCached;

/**
 * Class Measure
 * @package Lovata\Shopaholic\Models
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property int                                          $id
 * @property string                                       $name
 * @property string                                       $code
 * @property \October\Rain\Argon\Argon                    $created_at
 * @property \October\Rain\Argon\Argon                    $updated_at
 */
class Measure extends Model
{
    use NameField;
    use Validation;
    use TraitCached;

    public $table = 'lovata_shopaholic_measure';

    public $implement = [
        '@RainLab.Translate.Behaviors.TranslatableModel',
    ];

    public $translatable = ['name'];

    /** @var array Validation */
    public $rules = [
        'name' => 'required',
    ];

    public $attributeNames = [
        'name' => 'lovata.toolbox::lang.field.name',
    ];

    public $dates = ['created_at', 'updated_at'];

    public $hasMany = [];

    public $fillable = [
        'name',
        'code',
    ];

    public $cached = [
        'id',
        'name',
        'code',
    ];
}
