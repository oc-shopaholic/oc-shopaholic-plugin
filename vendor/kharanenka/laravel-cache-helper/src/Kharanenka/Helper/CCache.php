<?php namespace Kharanenka\Helper;

use Cache;
use Carbon\Carbon;

/**
 * Class CCache
 * @package Kharanenka\Helper
 * @author Andrey Kharanenka, kharanenka@gmail.com
 *
 */

class CCache {

    /**
     * Get cache value
     * @param array $arTags
     * @param string $sKeys
     * @return string|null
     */
    public static function get($arTags, $sKeys) {

        $sCacheDriver = config('cache.default');
        if(!empty($arTags)) {
            if($sCacheDriver == 'redis') {
                return Cache::tags($arTags)->get($sKeys);
            } else {
                $sKeys = implode('_', $arTags).'_'.$sKeys;
            }
        }

        return Cache::get($sKeys);
    }

    /**
     * Has cache value
     * @param array $arTags
     * @param string $sKeys
     * @return string|null
     */
    public static function has($arTags, $sKeys) {

        $sCacheDriver = config('cache.default');
        if(!empty($arTags)) {
            if($sCacheDriver == 'redis') {
                return Cache::tags($arTags)->has($sKeys);
            } else {
                $sKeys = implode('_', $arTags).'_'.$sKeys;
            }
        }

        return Cache::has($sKeys);
    }

    /**
     * Put cache data
     * @param array $arTags
     * @param string $sKeys
     * @param mixed $arValue
     * @param int $iMinute
     */
    public static function put($arTags, $sKeys, &$arValue, $iMinute) {

        $obDate = Carbon::now()->addMinute($iMinute);

        $sCacheDriver = config('cache.default');
        if(!empty($arTags)) {
            if($sCacheDriver == 'redis') {
                Cache::tags($arTags)->put($sKeys, $arValue, $obDate);
                return;
            } else {
                $sKeys = implode('_', $arTags).'_'.$sKeys;
            }
        }

        Cache::put($sKeys, $arValue, $obDate);
    }

    /**
     * Clear cache data
     * @param array $arTags
     * @param string $sKeys
     */
    public static function clear($arTags, $sKeys = null)
    {
        $sCacheDriver = config('cache.default');
        if(!empty($arTags)) {
            if($sCacheDriver == 'redis') {
                if(!empty($sKeys)) {
                    Cache::tags($arTags)->forget($sKeys);
                } else {
                    Cache::tags($arTags)->flush();
                }
            } else {
                $sKeys = implode('_', $arTags).'_'.$sKeys;
                Cache::forget($sKeys);
            }
        }
    }
}