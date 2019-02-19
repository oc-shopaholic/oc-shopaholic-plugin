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

    /** @var array */
    protected $arTaxPriceData = [];

    /** @var \RainLab\Location\Models\Country */
    protected $obActiveCountry;
    /** @var \RainLab\Location\Models\State */
    protected $obActiveState;

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
     * Get tax price value
     * @param \Lovata\Shopaholic\Classes\Item\OfferItem $obOfferItem
     * @return float
     */
    public function getTaxPrice($obOfferItem)
    {
        if (empty($obOfferItem)) {
            return 0;
        }

        $this->calculate($obOfferItem);

        $fPrice = (float) array_get($this->arTaxPriceData, $obOfferItem->id.'.tax_price');

        return $fPrice;
    }

    /**
     * Get price value without tax
     * @param \Lovata\Shopaholic\Classes\Item\OfferItem $obOfferItem
     * @return float
     */
    public function getPriceWithoutTax($obOfferItem)
    {
        if (empty($obOfferItem)) {
            return 0;
        }

        $this->calculate($obOfferItem);

        $fPrice = (float) array_get($this->arTaxPriceData, $obOfferItem->id.'.price_without_tax');

        return $fPrice;
    }

    /**
     * Get price value with tax
     * @param \Lovata\Shopaholic\Classes\Item\OfferItem $obOfferItem
     * @return float
     */
    public function getPriceWithTax($obOfferItem)
    {
        if (empty($obOfferItem)) {
            return 0;
        }

        $this->calculate($obOfferItem);

        $fPrice = (float) array_get($this->arTaxPriceData, $obOfferItem->id.'.price_with_tax');

        return $fPrice;
    }

    /**
     * Get tax percent
     * @param \Lovata\Shopaholic\Classes\Item\OfferItem $obOfferItem
     * @return float
     */
    public function getTaxPercent($obOfferItem)
    {
        if (empty($obOfferItem)) {
            return 0;
        }

        $this->calculate($obOfferItem);

        $fPrice = (float) array_get($this->arTaxPriceData, $obOfferItem->id.'.tax_percent');

        return $fPrice;
    }

    /**
     * Get applied tax list
     * @param \Lovata\Shopaholic\Classes\Item\OfferItem $obOfferItem
     * @return \Lovata\Shopaholic\Classes\Collection\TaxCollection|\Lovata\Shopaholic\Classes\Item\TaxItem[]
     */
    public function getAppliedTaxList($obOfferItem)
    {
        if (empty($obOfferItem)) {
            return TaxCollection::make()->clear();
        }

        $this->calculate($obOfferItem);

        $obTaxList = array_get($this->arTaxPriceData, $obOfferItem->id.'.tax_list');
        if (empty($obTaxList)) {
            return TaxCollection::make()->clear();
        }

        return $obTaxList;
    }

    /**
     * Init currency data
     */
    protected function init()
    {
        $this->obTaxList = TaxCollection::make()->active()->sort();

        $this->bPriceIncludeTax = (bool) Settings::getValue('price_include_tax');
    }

    /**
     * Calculate tax prices
     * @param \Lovata\Shopaholic\Classes\Item\OfferItem $obOfferItem
     */
    protected function calculate($obOfferItem)
    {
        if (isset($this->arTaxPriceData[$obOfferItem->id])) {
            return;
        }

        //Get available tax list
        $obTaxList = $this->getAvailableTaxList($obOfferItem);

        //Calculate tax percent
        $fTax = 0;
        foreach ($obTaxList as $obTaxItem) {
            $fTax += $obTaxItem->percent;
        }

        if ($this->bPriceIncludeTax) {
            $fPriceWithTax = $obOfferItem->price_value;
            $fPriceWithoutTax = $this->calculatePriceWithoutTax($obOfferItem->price_value, $fTax);
        } else {
            $fPriceWithTax = $this->calculatePriceWithTax($obOfferItem->price_value, $fTax);
            $fPriceWithoutTax = $obOfferItem->price_value;
        }

        $fTaxPrice = PriceHelper::round($fPriceWithTax - $fPriceWithoutTax);

        $this->arTaxPriceData[$obOfferItem->id] = [
            'tax_price'         => $fTaxPrice,
            'price_without_tax' => $fPriceWithoutTax,
            'price_with_tax'    => $fPriceWithTax,
            'tax_percent'       => $fTax,
            'tax_list'          => $obTaxList,
        ];
    }

    /**
     * Get tax percent for Offer object
     * @param \Lovata\Shopaholic\Classes\Item\OfferItem $obOfferItem
     * @return \Lovata\Shopaholic\Classes\Collection\TaxCollection|\Lovata\Shopaholic\Classes\Item\TaxItem[]
     */
    protected function getAvailableTaxList($obOfferItem)
    {
        $obResultTaxList = clone $this->obTaxList;
        if ($obResultTaxList->isEmpty()) {
            return $obResultTaxList;
        }

        foreach ($obResultTaxList as $obTaxItem) {
            $bSkipTax = !$obTaxItem->is_global
                && !$obTaxItem->isAvailableForCategory($obOfferItem->product->category)
                && !$obTaxItem->isAvailableForProduct($obOfferItem->product)
                && !$obTaxItem->isAvailableForCountry($this->obActiveCountry)
                && !$obTaxItem->isAvailableForState($this->obActiveState);

            if ($bSkipTax) {
                $obResultTaxList->exclude($obTaxItem->id);
            }
        }

        return $obResultTaxList;
    }

    /**
     * Calculate price + tax
     * @param float $fPrice
     * @param float $fTax
     * @return float
     */
    protected function calculatePriceWithTax($fPrice, $fTax)
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
    protected function calculatePriceWithoutTax($fPrice, $fTax)
    {
        $fPrice = ($fPrice * 100) / (100 + $fTax);
        $fPrice = PriceHelper::round($fPrice);

        return $fPrice;
    }
}
