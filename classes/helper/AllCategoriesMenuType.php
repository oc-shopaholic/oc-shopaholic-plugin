<?php namespace Lovata\Shopaholic\Classes\Helper;

use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Classes\Item\CategoryItem;

/**
 * Class AllCategoriesMenuType
 * @package Lovata\Shopaholic\Classes\Helper
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class AllCategoriesMenuType extends CommonMenuType
{
    const MENU_TYPE = 'all-shop-categories';

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

    /**
     * @inheritdoc
     */
    public function resolveMenuItem($obMenuItem, $sURL, $obTheme)
    {
        $arResult = [
            'items' => []
        ];

        //Get category list with sorted by 'nest_left'
        $obCategoryList = Category::active()->orderBy('nest_left', 'asc')->get();
        if ($obCategoryList->isEmpty()) {
            return $arResult;
        }

        /** @var Category $obCategory */
        foreach ($obCategoryList as $obCategory) {

            $obCategoryItem = CategoryItem::make($obCategory->id, $obCategory);

            $arMenuItem = $this->getCategoryMenuData($obCategoryItem, $obMenuItem->cmsPage, $sURL);
            $arResult['items'][] = $arMenuItem;
        }

        return $arResult;
    }
}
