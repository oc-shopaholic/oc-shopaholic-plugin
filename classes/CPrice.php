<?php namespace Lovata\Shopaholic\Classes;

use Lovata\Shopaholic\Models\Settings;

/**
 * Class CPrice
 * @package Lovata\Shopaholic\Classes
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CPrice {

    protected static $iDecimals = 2;
    protected static $sDecPoint = '.';
    protected static $sThousandsSep = ' ';

    /**
     * Custom format for price
     * @param $dPrice
     * @return mixed
     */
    public static function getPriceInFormat($dPrice)
    {
        $iDecimals = (int)Settings::getValue('decimals');
        if($iDecimals == null) {
            self::$iDecimals = $iDecimals;
        }
        
        $sDecPoint = Settings::getValue('dec_point');
        if(!empty($sDecPoint)) {
            self::$sDecPoint = $sDecPoint;
            switch($sDecPoint) {
                case 'dot':
                    self::$sDecPoint = '.';
                    break;
                case 'comma':
                    self::$sDecPoint = ',';
                    break;
                default:
                    self::$sDecPoint = '.';
            }
        }
        
        $sThousandsSep = Settings::getValue('thousands_sep');
        if(!empty($sThousandsSep)) {
            self::$sThousandsSep = $sThousandsSep;
            switch($sThousandsSep) {
                case 'together':
                    self::$sThousandsSep = '';
                    break;
                case 'space':
                    self::$sThousandsSep = ' ';
                    break;
                case 'double_space':
                    self::$sThousandsSep = '  ';
                    break;
                case 'hyphen':
                    self::$sThousandsSep = '-';
                    break;
                default:
                    self::$sThousandsSep = '';
            }
        }
        
        return number_format($dPrice, self::$iDecimals, self::$sDecPoint, self::$sThousandsSep);
    }
}