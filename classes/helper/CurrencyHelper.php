<?php namespace Lovata\Shopaholic\Classes\Helper;

use October\Rain\Support\Traits\Singleton;

use Lovata\Toolbox\Classes\Helper\PriceHelper;
use Lovata\Toolbox\Classes\Storage\CookieUserStorage;
use Lovata\Toolbox\Classes\Storage\UserStorage;

use Lovata\Shopaholic\Models\Currency;

/**
 * Class CurrencyHelper
 * @package Lovata\Shopaholic\Classes\Helper
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CurrencyHelper
{
    use Singleton;

    const FIELD_NAME = 'active_currency_code';

    /** @var Currency */
    protected $obActiveCurrency;

    /** @var Currency */
    protected $obDefaultCurrency;

    /** @var \October\Rain\Database\Collection|Currency[] */
    protected $obCurrencyList;

    /** @var string */
    protected $sActiveCurrencyCode;

    /**
     * Get value of active currency
     * @return Currency
     */
    public function getActive()
    {
        return $this->obActiveCurrency;
    }

    /**
     * Get default currency object
     * @return Currency
     */
    public function getDefault()
    {
        return $this->obDefaultCurrency;
    }

    /**
     * Set default currency as active
     * Used in backend
     */
    public function disableActiveCurrency()
    {
        if (empty($this->obDefaultCurrency)) {
            return;
        }

        $this->sActiveCurrencyCode = $this->obDefaultCurrency->code;
        $this->obActiveCurrency = $this->obDefaultCurrency;
    }

    /**
     * Get active currency symbol
     * @return null|string
     */
    public function getActiveCurrencySymbol()
    {
        $obCurrency = $this->getActive();
        if (empty($obCurrency)) {
            return null;
        }

        return $obCurrency->symbol;
    }

    /**
     * Get active currency code
     * @return null|string
     */
    public function getActiveCurrencyCode()
    {
        $obCurrency = $this->getActive();
        if (empty($obCurrency)) {
            return null;
        }

        return $obCurrency->code;
    }

    /**
     * Get currency symbol
     * @param string $sCurrencyCode
     * @return null|string
     */
    public function getCurrencySymbol($sCurrencyCode)
    {
        $obCurrency = $this->obCurrencyList->where('code', $sCurrencyCode)->first();
        if (empty($obCurrency)) {
            return null;
        }

        return $obCurrency->symbol;
    }

    /**
     * Get currency code
     * @param string $sCurrencyCode
     * @return null|string
     */
    public function getCurrencyCode($sCurrencyCode)
    {
        $obCurrency = $this->obCurrencyList->where('code', $sCurrencyCode)->first();
        if (empty($obCurrency)) {
            return null;
        }

        return $obCurrency->code;
    }

    /**
     * Clear active currency value
     * @param string $sCurrencyCode
     */
    public function switchActive($sCurrencyCode)
    {
        $obUserStorage = $this->getUserStorage();
        $obUserStorage->put(self::FIELD_NAME, $sCurrencyCode);
        $this->sActiveCurrencyCode = $sCurrencyCode;

        $this->initActiveCurrency();
    }

    /**
     * Clear active currency value
     */
    public function resetActive()
    {
        $obUserStorage = $this->getUserStorage();
        $obUserStorage->clear(self::FIELD_NAME);

        $this->obActiveCurrency = $this->obDefaultCurrency;
    }

    /**
     * @param float  $fPrice
     * @param string $sCurrencyTo
     * @return float
     */
    public function convert($fPrice, $sCurrencyTo = null)
    {
        if (empty($sCurrencyTo)) {
            $obCurrencyTo = $this->obActiveCurrency;
        } else {
            $obCurrencyTo = $this->obCurrencyList->where('code', $sCurrencyTo)->first();
        }

        if (empty($obCurrencyTo) || empty($this->obDefaultCurrency) || $obCurrencyTo->id == $this->obDefaultCurrency->id) {
            return $fPrice;
        }

        $fResultPrice = PriceHelper::round(($this->obDefaultCurrency->rate * $fPrice) / $obCurrencyTo->rate);

        return $fResultPrice;
    }

    /**
     * Init currency data
     */
    protected function init()
    {
        $this->obCurrencyList = Currency::active()->get();
        $this->obDefaultCurrency = $this->obCurrencyList->where('is_default', true)->first();

        $this->sActiveCurrencyCode = $this->getActiveCurrencyFromStorage();
        $this->initActiveCurrency();
    }

    /**
     * Get active currency code and find active currency object by code
     */
    protected function initActiveCurrency()
    {
        $this->obActiveCurrency = null;
        if (!empty($this->sActiveCurrencyCode)) {
            $this->obActiveCurrency = $this->obCurrencyList->where('code', $this->sActiveCurrencyCode)->first();
        }

        if (empty($this->obActiveCurrency)) {
            $this->obActiveCurrency = $this->obDefaultCurrency;
        }
    }

    /**
     * Get active currency code from user storage
     * @return array
     */
    protected function getActiveCurrencyFromStorage()
    {
        $obUserStorage = $this->getUserStorage();
        $sActiveCurrencyCode = $obUserStorage->get(self::FIELD_NAME);

        return $sActiveCurrencyCode;
    }

    /**
     * Get user storage object (User model or cookie)
     * @return UserStorage
     */
    protected function getUserStorage()
    {
        /** @var UserStorage $obUserStorage */
        $obUserStorage = app(UserStorage::class);
        $obUserStorage->setDefaultStorage(CookieUserStorage::class);

        return $obUserStorage;
    }
}
