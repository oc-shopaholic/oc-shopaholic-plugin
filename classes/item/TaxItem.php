<?php namespace Lovata\Shopaholic\Classes\Item;

use System\Classes\PluginManager;
use Lovata\Toolbox\Classes\Item\ElementItem;

use Lovata\Shopaholic\Models\Tax;

/**
 * Class TaxItem
 * @package Lovata\Shopaholic\Classes\Item
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property int    $id
 * @property bool   $is_global
 * @property string $name
 * @property string $description
 * @property float  $percent
 * @property array  $category_id_list
 * @property array  $product_id_list
 * @property array  $country_id_list
 * @property array  $state_id_list
 *
 * Orders for Shopaholic
 * @property bool   $applied_to_shipping_price
 */
class TaxItem extends ElementItem
{
    const MODEL_CLASS = Tax::class;

    /** @var Tax */
    protected $obElement = null;

    /**
     * Check tax is available for category
     * @param \Lovata\Shopaholic\Classes\Item\CategoryItem $obCategoryItem
     * @return bool
     */
    public function isAvailableForCategory($obCategoryItem) : bool
    {
        $arCategoryIDList = (array) $this->category_id_list;
        if (empty($arCategoryIDList) || empty($obCategoryItem)) {
            return false;
        }

        if (in_array($obCategoryItem->id, $arCategoryIDList)) {
            return true;
        }

        if ($obCategoryItem->parent->isNotEmpty()) {
            return $this->isAvailableForCategory($obCategoryItem->parent);
        }

        return false;
    }

    /**
     * Check tax is available for product
     * @param \Lovata\Shopaholic\Classes\Item\ProductItem $obProductItem
     * @return bool
     */
    public function isAvailableForProduct($obProductItem) : bool
    {
        $arProductIDList = (array) $this->product_id_list;

        $bResult = !empty($arProductIDList) && !empty($obProductItem) && in_array($obProductItem->id, $arProductIDList);

        return $bResult;
    }

    /**
     * Check tax is available for country
     * @param \RainLab\Location\Models\Country $obCountry
     * @return bool
     */
    public function isAvailableForCountry($obCountry) : bool
    {
        $arCountryIDList = (array) $this->country_id_list;

        $bResult = !empty($arCountryIDList) && !empty($obCountry) && in_array($obCountry->id, $arCountryIDList);

        return $bResult;
    }

    /**
     * Check tax is available for state
     * @param \RainLab\Location\Models\State $obState
     * @return bool
     */
    public function isAvailableForState($obState) : bool
    {
        $arStateIDList = (array) $this->state_id_list;

        $bResult = !empty($arStateIDList) && !empty($obState) && in_array($obState->id, $arStateIDList);

        return $bResult;
    }

    /**
     * Set model data from object
     * @return mixed
     */
    protected function getElementData()
    {
        $arResult = [
            'category_id_list' => $this->obElement->category()->lists('id'),
            'product_id_list'  => $this->obElement->product()->lists('id'),
        ];

        if (PluginManager::instance()->hasPlugin('RainLab.Location')) {
            $arResult['country_id_list'] = $this->obElement->country()->pluck('id')->all();
            $arResult['state_id_list'] = $this->obElement->state()->pluck('id')->all();
        }

        return $arResult;
    }
}
