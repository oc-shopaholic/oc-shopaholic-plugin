<?php namespace Lovata\Shopaholic\Classes\Store;

use Kharanenka\Helper\CCache;

use Lovata\Toolbox\Traits\Store\TraitActiveList;

use Lovata\Shopaholic\Plugin;
use Lovata\Shopaholic\Models\Offer;

/**
 * Class OfferListStore
 * @package Lovata\Shopaholic\Classes\Store
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class OfferListStore
{
    use TraitActiveList;
    
    const CACHE_TAG_LIST = 'shopaholic-offer-list';

    const SORT_NO = 'no';
    const SORT_PRICE_ASC = 'price|asc';
    const SORT_PRICE_DESC = 'price|desc';
    const SORT_NEW = 'new';

    /**
     * Get available sorting value list
     * @return array
     */
    public function getAvailableSorting()
    {
        return [
            self::SORT_NO,
            self::SORT_PRICE_ASC,
            self::SORT_PRICE_DESC,
            self::SORT_NEW,
        ];
    }

    /**
     * Get offer ID list by sorting value
     * @param string $sSorting
     * @return array
     */
    public function getBySorting($sSorting)
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_LIST];
        $sCacheKey = $sSorting;

        $arOfferIDList = CCache::get($arCacheTags, $sCacheKey);
        if(!empty($arOfferIDList)) {
            return $arOfferIDList;
        }

        return $this->updateCacheBySorting($sSorting);
    }

    /**
     * Update cache offer ID list by sorting and get new
     * @param string $sSorting
     * @return array|null
     */
    public function updateCacheBySorting($sSorting)
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, self::CACHE_TAG_LIST];
        $sCacheKey = $sSorting;

        switch($sSorting) {
            case self::SORT_PRICE_ASC :

                /** @var array $arOfferIDList */
                $arOfferIDList = Offer::orderBy('price', 'asc')->lists('id');
                if(empty($arOfferIDList)) {
                    return null;
                }

                break;
            case self::SORT_PRICE_DESC :

                /** @var array $arOfferIDList */
                $arOfferIDList = Offer::orderBy('price', 'desc')->lists('id');
                if(empty($arOfferIDList)) {
                    return null;
                }
                
                break;
            case self::SORT_NEW :
                $arOfferIDList = Offer::orderBy('id', 'desc')->lists('id');
                break;
            default:
                $arOfferIDList = Offer::lists('id');
                break;
        }

        if(empty($arOfferIDList)) {
            return null;
        }

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arOfferIDList);

        return $arOfferIDList;
    }
    
    /**
     * Get offer active ID list
     * @return array
     */
    protected function getActiveIDList()
    {
        /** @var array $arOfferIDList */
        $arOfferIDList = Offer::active()->lists('id');
        return $arOfferIDList;
    }
}