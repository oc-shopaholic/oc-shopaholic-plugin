<?php namespace Lovata\Shopaholic\Classes\Helper;

use Event;
use Lovata\Shopaholic\Models\Settings;
use October\Rain\Support\Traits\Singleton;

/**
 * Class CurrencyHelper
 * @package Lovata\Shopaholic\Classes\Helper
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CurrencyHelper
{
    use Singleton;

    const EVENT_GET_ACTIVE_CURRENCY_VALUE     = 'shopaholic.currency.get_active_value';
    const EVENT_GET_ACTIVE_ISO_CURRENCY_VALUE = 'shopaholic.iso_currency.get_active_value';

    /** @var string */
    protected $sActiveCurrency;
    /** @var string */
    protected $sActiveIsoCurrency;

    /**
     * Get value of active currency
     * @return string
     */
    public function getActive()
    {
        return $this->sActiveCurrency;
    }

    /**
     * Get value of active iso_currency
     * @return string
     */
    public function getIsoActive()
    {
        return $this->sActiveIsoCurrency;
    }

    /**
     * Init currency data
     */
    protected function init()
    {
        $this->sActiveCurrency = Event::fire(self::EVENT_GET_ACTIVE_CURRENCY_VALUE, null, true);
        $this->sActiveIsoCurrency = Event::fire(self::EVENT_GET_ACTIVE_ISO_CURRENCY_VALUE, null, true);
        if (empty($this->sActiveCurrency)) {
            $this->sActiveCurrency = Settings::getValue('currency');
        }
        if (empty($this->sActiveIsoCurrency)) {
            $this->sActiveIsoCurrency = Settings::getValue('iso_currency');
        }
    }
}
