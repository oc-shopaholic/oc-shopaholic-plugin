<?php namespace Lovata\Shopaholic\Classes\Item;

use Lovata\Toolbox\Classes\Item\ElementItem;

use Lovata\Shopaholic\Models\Currency;
use Lovata\Shopaholic\Classes\Helper\CurrencyHelper;

/**
 * Class CurrencyItem
 * @package Lovata\Shopaholic\Classes\Item
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property int    $id
 * @property bool   $is_default
 * @property string $name
 * @property string $code
 * @property string $symbol
 * @property float  $rate
 */
class CurrencyItem extends ElementItem
{
    const MODEL_CLASS = Currency::class;

    /** @var Currency */
    protected $obElement = null;

    /**
     * Check, currency is active
     * @return bool
     */
    public function isActive()
    {
        $bResult = $this->code == CurrencyHelper::instance()->getActiveCurrencyCode();

        return $bResult;
    }
}
