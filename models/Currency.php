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

use Lovata\Shopaholic\Classes\Helper\CurrencyHelper;

/**
 * Class Currency
 * @package Lovata\Shopaholic\Models
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property                                    $id
 * @property bool                               $active
 * @property bool                               $is_default
 * @property string                             $name
 * @property string                             $code
 * @property string                             $symbol
 * @property float                              $rate
 * @property string                             $external_id
 * @property int                                $sort_order
 * @property \October\Rain\Argon\Argon          $created_at
 * @property \October\Rain\Argon\Argon          $updated_at
 * @property \October\Rain\Argon\Argon          $deleted_at
 *
 * @method static $this isDefault()
 */
class Currency extends Model
{
    use Validation;
    use SoftDelete;
    use Sortable;
    use ActiveField;
    use NameField;
    use CodeField;
    use ExternalIDField;
    use TraitCached;

    public $table = 'lovata_shopaholic_currency';

    public $rules = [
        'name'   => 'required',
        'symbol' => 'required',
        'code'   => 'required|unique:lovata_shopaholic_currency',
    ];

    public $attributeNames = [
        'name'   => 'lovata.toolbox::lang.field.name',
        'code'   => 'lovata.toolbox::lang.field.code',
        'symbol' => 'lovata.shopaholic::lang.field.currency_symbol',
    ];

    public $attachOne = [];
    public $attachMany = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphMany = [];

    public $dates = ['created_at', 'updated_at', 'deleted_at'];

    public $appends = [];
    public $purgeable = [];

    public $fillable = [
        'active',
        'is_default',
        'name',
        'code',
        'symbol',
        'rate',
        'external_id',
        'sort_order',
    ];

    public $cached = [
        'id',
        'is_default',
        'name',
        'code',
        'rate',
        'symbol',
    ];

    public $visible = [];
    public $hidden = [];

    /**
     * Check, currency is active
     * @return bool
     */
    public function isActive()
    {
        $bResult = $this->code == CurrencyHelper::instance()->getActiveCurrencyCode();

        return $bResult;
    }

    /**
     * After save model event
     */
    public function afterSave()
    {
        if ($this->is_default && !$this->getOriginal('is_default')) {
            $this->disableDefaultCurrency();
        }
    }

    /**
     * Get element with is_default flag == true
     * @param Currency $obQuery
     * @return Currency
     */
    public function scopeIsDefault($obQuery)
    {
        return $obQuery->where('is_default', true);
    }

    /**
     * Set rate attribute
     * @param string $sValue
     */
    protected function setRateAttribute($sValue)
    {
        $this->attributes['rate'] = PriceHelper::toFloat($sValue);
    }

    /**
     * Disable default currency, because only one can be an active currency
     */
    protected function disableDefaultCurrency()
    {
        $obCurrencyList = Currency::isDefault()->get();
        if ($obCurrencyList->isEmpty()) {
            return;
        }

        /** @var Currency $obCurrency */
        foreach ($obCurrencyList as $obCurrency) {
            if ($obCurrency->id == $this->id) {
                continue;
            }

            $obCurrency->is_default = false;
            $obCurrency->save();
        }
    }
}
