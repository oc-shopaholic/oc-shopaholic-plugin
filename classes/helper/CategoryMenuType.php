<?php namespace Lovata\Shopaholic\Classes\Helper;

use Lovata\Shopaholic\Classes\Item\CategoryItem;

/**
 * Class CategoryMenuType
 * @package Lovata\Shopaholic\Classes\Helper
 *
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * @author Alvaro Cánepa, https://github.com/alvaro-canepa
 */
class CategoryMenuType extends CommonMenuType
{
    const MENU_TYPE = 'shop-category';

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

        $obCategoryItem = CategoryItem::make($obMenuItem->reference);
        if ($obCategoryItem->isEmpty()) {
            return $arResult;
        }

        $arResult = $this->getCategoryMenuData($obCategoryItem, $obMenuItem->cmsPage, $sURL);
        if (!$obMenuItem->nesting || $obCategoryItem->children->isEmpty()) {
            return $arResult;
        }

        $arResult['items'] = $this->getChildrenCategoryList($obCategoryItem, $obMenuItem->cmsPage, $sURL);

        return $arResult;
    }

    /**
     * Get default array for menu type
     * @return array|null
     */
    protected function getDefaultMenuTypeInfo()
    {
        $arResult = [
            'references'   => $this->listSubCategoryOptions(),
            'nesting'      => true,
            'dynamicItems' => true,
        ];

        return $arResult;
    }
}
