<?php namespace Lovata\Shopaholic\Classes;

use Lovata\Shopaholic\Models\Settings;

/**
 * Class CPrice
 * @package Lovata\Shopaholic\Classes
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CPrice
{
    /** @var CPrice */
    protected static $obThis = null;

    /** @var int */
    protected $iDecimals = 2;

    /** @var string  */
    protected $sDecPoint = '.';

    /** @var string  */
    protected $sThousandsSep = ' ';

    /**
     * CPrice constructor.
     */
    protected function __construct()
    {
        //Get options from settings
        $this->iDecimals = (int) Settings::getValue('decimals');

        $sDecPoint = Settings::getValue('dec_point');
        switch($sDecPoint) {
            case 'dot':
                $this->sDecPoint = '.';
                break;
            case 'comma':
                $this->sDecPoint = ',';
                break;
            default:
                $this->sDecPoint = '.';
        }

        $sThousandsSep = Settings::getValue('thousands_sep');
        switch($sThousandsSep) {
            case 'together':
                $this->sThousandsSep = '';
                break;
            case 'space':
                $this->sThousandsSep = ' ';
                break;
            case 'double_space':
                $this->sThousandsSep = '  ';
                break;
            case 'hyphen':
                $this->sThousandsSep = '-';
                break;
            default:
                $this->sThousandsSep = '';
        }
    }

    /**
     * Get instance CPrice
     * @return CPrice
     */
    protected static function getInstance()
    {
        if(self::$obThis === null) {
            self::$obThis = new CPrice();
        }

        return self::$obThis;
    }

    /**
     * Apply custom format for price
     * @param float $fPrice
     * @return string
     */
    public static function get($fPrice)
    {
        $obThis = self::getInstance();
        return number_format($fPrice, $obThis->iDecimals, $obThis->sDecPoint, $obThis->sThousandsSep);
    }
}