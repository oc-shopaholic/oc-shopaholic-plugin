<?php namespace Kharanenka\Helper;

/**
 * Generate universal result answer
 *
 * Class Result
 * @package Kharanenka\Helper
 * @author Andrey Kharanenka, kharanenka@gmail.com
 *
 * @method static ResultStore setTrue(mixed $obData = null)
 * @method static ResultStore setFalse(mixed $obData = null)
 * @method static bool flag()
 * @method static mixed data()
 * @method static array get()
 * @method static string getJSON()
 */

class Result {

    public static function __callStatic($sMethodName, $arArguments)
    {
        $obResult = ResultStore::getInstance();
        if(empty($arArguments)) {
            return $obResult->$sMethodName();
        }

        return call_user_func_array([$obResult, $sMethodName], $arArguments);
    }
}