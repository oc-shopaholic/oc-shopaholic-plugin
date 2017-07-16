<?php namespace Lovata\Shopaholic\Classes\Store;

use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Plugin;

/**
 * Class OfferListStore
 * @package Lovata\Shopaholic\Classes\Store
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class OfferListStore
{
    const CACHE_TAG_LIST = 'shopaholic-offer-list';

    /**
     * Get active offer ID list
     * @return array|null
     */
    public function getActiveList()
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_LIST];
        $sCacheKey = self::CACHE_TAG_LIST;

        $arOfferIDList = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arOfferIDList)) {
            return $arOfferIDList;
        }

        //Get offer ID list
        /** @var array $arOfferIDList */
        $arOfferIDList = Offer::active()->lists('id');
        if(empty($arOfferIDList)) {
            return null;
        }

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arOfferIDList);

        return $arOfferIDList;
    }
}