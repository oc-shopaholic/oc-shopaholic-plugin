<?php namespace Lovata\Shopaholic\Classes\Store\Brand;

use Lovata\Shopaholic\Models\Category;
use Lovata\Toolbox\Classes\Store\AbstractStoreWithParam;

use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Store\ProductListStore;

/**
 * Class ListByCategoryStore
 * @package Lovata\Shopaholic\Classes\Store\Brand
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ListByCategoryStore extends AbstractStoreWithParam
{
    protected static $instance;

    /**
     * Get ID list from database
     * @return array
     */
    protected function getIDListFromDB() : array
    {
        $arElementIDList = (array) Product::getByCategory($this->sValue)
            ->where('brand_id', '>', 0)
            ->pluck('brand_id', 'id')->all();

        $obCategory = Category::find($this->sValue);
        if (!empty($obCategory)) {
            $arAdditionalElementIDList = (array) $obCategory->product_link()
                ->where('brand_id', '>', 0)
                ->pluck('brand_id', 'id')->all();

            $arElementIDList = $arElementIDList + $arAdditionalElementIDList;
        }

        return $arElementIDList;
    }

    /**
     * Get ID list from cache
     * @return array
     */
    protected function getIDListFromCache() : array
    {
        $arElementIDList = parent::getIDListFromCache();

        //Get active product list
        $arActiveProductIDList = ProductListStore::instance()->active->get();
        if (empty($arActiveProductIDList) || empty($arElementIDList)) {
            return [];
        }

        $arResult = [];
        foreach ($arElementIDList as $iProductID => $iBrandID) {
            if (!in_array($iProductID, $arActiveProductIDList)) {
                continue;
            }

            $arResult[] = $iBrandID;
        }

        $arResult = array_unique($arResult);

        return $arResult;
    }
}
