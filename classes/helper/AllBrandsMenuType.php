<?php namespace Lovata\Shopaholic\Classes\Helper;

use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Models\Brand;

/**
 * Class BrandMenuType
 * @package Lovata\Shopaholic\Classes\Helper
 *
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * @author Alvaro Cánepa, https://github.com/alvaro-canepa
 */
class AllBrandsMenuType extends CommonMenuType
{
    const MENU_TYPE = 'all-shop-brands';

    /**
     * Handler for the pages.menuitem.resolveItem event.
     * @param \RainLab\Pages\Classes\MenuItem $obMenuItem
     * @param string                          $sURL
     *
     * @return array|mixed
     */
    public function resolveMenuItem($obMenuItem, $sURL)
    {
        $arResult = [
            'items' => [],
        ];

        //Get category list with sorted by 'nest_left'
        $obBrandList = Brand::active()->orderBy('sort_order', 'asc')->get();
        if ($obBrandList->isEmpty()) {
            return $arResult;
        }

        /** @var Brand $obBrand */
        foreach ($obBrandList as $obBrand) {
            $obBrandItem = BrandItem::make($obBrand->id, $obBrand);

            $arMenuItem = $this->getBrandMenuData($obBrandItem, $obMenuItem->cmsPage, $sURL);
            $arResult['items'][] = $arMenuItem;
        }

        return $arResult;
    }

    /**
     * Get default array for menu type
     * @return array|null
     */
    protected function getDefaultMenuTypeInfo()
    {
        $arResult = [
            'dynamicItems' => true,
        ];

        return $arResult;
    }
}
