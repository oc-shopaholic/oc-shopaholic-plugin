<?php namespace Lovata\Shopaholic\Classes\Helper;

use System\Classes\PluginManager;
use October\Rain\Support\Traits\Singleton;

use Lovata\Toolbox\Classes\Helper\PriceHelper;

use Lovata\Shopaholic\Models\Settings;
use Lovata\Shopaholic\Classes\Collection\TaxCollection;

/**
 * Class TaxHelper
 * @package Lovata\Shopaholic\Classes\Helper
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class TaxHelper
{
    use Singleton;

    /** @var \Lovata\Shopaholic\Classes\Collection\TaxCollection|\Lovata\Shopaholic\Classes\Item\TaxItem[] */
    protected $obTaxList;

    /** @var bool */
    protected $bPriceIncludeTax;

    /** @var \RainLab\Location\Models\Country */
    protected $obActiveCountry;
    /** @var \RainLab\Location\Models\State */
    protected $obActiveState;

    /**
     * Return flag "price include tax" from settings
     * @return bool
     */
    public function isPriceIncludeTax()
    {
        return $this->bPriceIncludeTax;
    }

    /**
     * Switch active country by code
     * @param string $sCountryCode
     */
    public function switchCountry($sCountryCode)
    {
        if (empty($sCountryCode) || !PluginManager::instance()->hasPlugin('RainLab.Location')) {
            return;
        }

        $this->obActiveCountry = \RainLab\Location\Models\Country::whereCode($sCountryCode)->first();
    }

    /**
     * Get active country object
     * @return \RainLab\Location\Models\Country
     */
    public function getActiveCountry()
    {
        return $this->obActiveCountry;
    }

    /**
     * Switch active state by code
     * @param string $sStateCode
     */
    public function switchState($sStateCode)
    {
        if (empty($sStateCode) || !PluginManager::instance()->hasPlugin('RainLab.Location')) {
            return;
        }

        $this->obActiveState = \RainLab\Location\Models\State::whereCode($sStateCode)->first();
    }

    /**
     * Get active state object
     * @return \RainLab\Location\Models\State
     */
    public function getActiveState()
    {
        return $this->obActiveState;
    }

    /**
     * Get applied tax list
     * @return \Lovata\Shopaholic\Classes\Collection\TaxCollection|\Lovata\Shopaholic\Classes\Item\TaxItem[]
     */
    public function getTaxList()
    {
        return clone  $this->obTaxList;
    }

    /**
     * Get price value without tax
     * @param float $fPrice
     * @param float $fPricePercent
     * @return float
     */
    public function getPriceWithoutTax($fPrice, $fPricePercent)
    {
        $fPriceResult = $fPrice;
        if ($this->bPriceIncludeTax) {
            $fPriceResult = $this->calculatePriceWithoutTax($fPrice, $fPricePercent);
        }

        return $fPriceResult;
    }

    /**
     * Get price value with tax
     * @param float $fPrice
     * @param float $fPricePercent
     * @return float
     */
    public function getPriceWithTax($fPrice, $fPricePercent)
    {
        $fPriceResult = $fPrice;
        if (!$this->bPriceIncludeTax) {
            $fPriceResult = $this->calculatePriceWithTax($fPrice, $fPricePercent);
        }

        return $fPriceResult;
    }

    /**
     * Calculate price + tax
     * @param float $fPrice
     * @param float $fTax
     * @return float
     */
    public function calculatePriceWithTax($fPrice, $fTax)
    {
        $fPrice = ($fPrice * (100 + $fTax)) / 100;
        $fPrice = PriceHelper::round($fPrice);

        return $fPrice;
    }

    /**
     * Calculate price - tax
     * @param float $fPrice
     * @param float $fTax
     * @return float
     */
    public function calculatePriceWithoutTax($fPrice, $fTax)
    {
        $fPrice = ($fPrice * 100) / (100 + $fTax);
        $fPrice = PriceHelper::round($fPrice);

        return $fPrice;
    }

    /**
     * Get tax percent
     * @param \Lovata\Shopaholic\Classes\Collection\TaxCollection|\Lovata\Shopaholic\Classes\Item\TaxItem[] $obTaxList
     * @return float
     */
    public function getTaxPercent($obTaxList)
    {
        if (empty($obTaxList) || $obTaxList->isEmpty()) {
            return 0;
        }

        //Calculate tax percent
        $fTaxPercent = 0;
        foreach ($obTaxList as $obTaxItem) {
            $fTaxPercent += $obTaxItem->percent;
        }

        $fTaxPercent = PriceHelper::round($fTaxPercent);

        return $fTaxPercent;
    }

    /**
     * Init currency data
     */
    protected function init()
    {
        $this->obTaxList = TaxCollection::make()->active()->sort();

        $this->bPriceIncludeTax = (bool) Settings::getValue('price_include_tax');
    }
}
