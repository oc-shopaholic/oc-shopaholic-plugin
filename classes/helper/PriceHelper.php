<?php namespace Lovata\Shopaholic\Classes\Helper;

use Lovata\Shopaholic\Models\Settings;

/**
 * Class PriceHelper
 * @package Lovata\Shopaholic\Classes\Helper
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class PriceHelper
{
    /** @var int */
    protected $iDecimal = 2;

    /** @var string  */
    protected $sDecPoint = '.';

    /** @var string  */
    protected $sThousandsSep = ' ';

    /**
     * PriceHelper constructor.
     */
    public function __construct()
    {
        //Get options from settings
        $iDecimal = Settings::getValue('decimals');
        if($iDecimal != null) {
            $this->iDecimal = (int) $iDecimal;
        }

        $sDecPoint = Settings::getValue('dec_point');
        switch($sDecPoint) {
            case 'comma':
                $this->sDecPoint = ',';
                break;
            default:
                $this->sDecPoint = '.';
        }

        $sThousandsSep = Settings::getValue('thousands_sep');
        switch($sThousandsSep) {
            case 'space':
                $this->sThousandsSep = ' ';
                break;
            default:
                $this->sThousandsSep = '';
        }
    }

    /**
     * Apply custom format for price
     * @param float $fPrice
     * @return string
     */
    public function get($fPrice)
    {
        return number_format($fPrice, $this->iDecimal, $this->sDecPoint, $this->sThousandsSep);
    }
}