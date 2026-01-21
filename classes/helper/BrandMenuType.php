<?php namespace Lovata\Shopaholic\Classes\Helper;

use Cms\Classes\Page as CmsPage;
use Lovata\Shopaholic\Classes\Collection\BrandCollection;
use Lovata\Shopaholic\Classes\Item\BrandItem;

/**
 * Class BrandMenuType
 * @package Lovata\Shopaholic\Classes\Helper
 *
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * @author Alvaro Cánepa, https://github.com/alvaro-canepa
 */
class BrandMenuType extends CommonMenuType
{
    const MENU_TYPE = 'shop-brand';

    /**
     * Handler for the pages.menuitem.resolveItem event.
     * @param \RainLab\Pages\Classes\MenuItem $obMenuItem
     * @param string                          $sURL
     * @return array|mixed
     */
    public function resolveMenuItem($obMenuItem, $sURL)
    {

        $arResult = [];
        if (empty($obMenuItem->reference)) {
            return $arResult;
        }

        $obBrandItem = BrandItem::make($obMenuItem->reference);
        if ($obBrandItem->isEmpty()) {
            return $arResult;
        }

        $arResult = $this->getBrandMenuData($obBrandItem, $obMenuItem->cmsPage, $sURL);

        return $arResult;
    }

    /**
     * Get subcategories list
     * @return array
     */
    protected function listBrands()
    {
        $arResult = [];
        $obBrandList = BrandCollection::make()->active();
        if ($obBrandList->isEmpty()) {
            return $arResult;
        }

        $arResult = $this->getBrandMenuOptions($obBrandList);

        return $arResult;
    }

    /**
     * Get option array for category
     * @param BrandCollection|\Lovata\Shopaholic\Classes\Item\BrandItem[] $obBrandList
     * @return null|array|string
     */
    protected function getBrandMenuOptions($obBrandList)
    {
        if (empty($obBrandList) || $obBrandList->isEmpty()) {
            return null;
        }

        $arResult = [];
        foreach ($obBrandList as $obBrand) {
            $arResult[$obBrand->id] = [
                'title' => $obBrand->name,
            ];
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
            'references'   => $this->listBrands(),
        ];

        return $arResult;
    }

    /**
     * Filter page list, add pages with CategoryPage component to result
     * @param \October\Rain\Support\Collection $obPageList
     * @return array
     */
    protected function filterPageList($obPageList)
    {
        $arCmsPageList = [];
        if (empty($obPageList) || $obPageList->isEmpty()) {
            return $arCmsPageList;
        }

        /** @var CmsPage $obPage */
        foreach ($obPageList as $obPage) {
            if (!$obPage->hasComponent('BrandPage')) {
                continue;
            }

            /*
             * Component must use a category filter with a routing parameter
             * eg: categoryFilter = "{{ :somevalue }}"
             */
            $arPropertyList = $obPage->getComponentProperties('BrandPage');
            if (!isset($arPropertyList['slug']) || !preg_match('/{{\s*:/', $arPropertyList['slug'])) {
                continue;
            }
            $arCmsPageList[] = $obPage;
        }

        return $arCmsPageList;
    }
}
