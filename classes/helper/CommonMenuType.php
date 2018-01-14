<?php namespace Lovata\Shopaholic\Classes\Helper;

use Cms\Classes\Theme;
use Cms\Classes\Page as CmsPage;
use Lovata\Shopaholic\Classes\Collection\CategoryCollection;

/**
 * Class CommonMenuType
 * @package Lovata\Shopaholic\Classes\Helper
 *
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * @author Alvaro Cánepa, https://github.com/alvaro-canepa
 */
abstract class CommonMenuType
{
    /**
     * Handler for the pages.menuitem.resolveItem event.
     * Returns information about a menu item. The result is an array
     * with the following keys:
     * - url - the menu item URL. Not required for menu item types that return all available records.
     *   The URL should be returned relative to the website root and include the subdirectory, if any.
     *   Use the URL::to() helper to generate the URLs.
     * - isActive - determines whether the menu item is active. Not required for menu item types that
     *   return all available records.
     * - items - an array of arrays with the same keys (url, isActive, items) + the title key.
     *   The items array should be added only if the $item's $nesting property value is TRUE.
     *
     * @param \RainLab\Pages\Classes\MenuItem $obItem Specifies the menu item.
     * @param string                          $sURL   Specifies the current page URL, normalized, in lower case
     *
     * The URL is specified relative to the website root, it includes the subdirectory name, if any.
     * @return mixed Returns an array. Returns null if the item cannot be resolved.
     */
    abstract public function resolveMenuItem($obItem, $sURL);

    /**
     * Handler for the pages.menuitem.getTypeInfo event.
     * Returns a menu item type information. The type information is returned as array
     * with the following elements:
     * - references - a list of the item type reference options. The options are returned in the
     *   ["key"] => "title" format for options that don't have sub-options, and in the format
     *   ["key"] => ["title"=>"Option title", "items"=>[...]] for options that have sub-options. Optional,
     *   required only if the menu item type requires references.
     * - nesting - Boolean value indicating whether the item type supports nested items. Optional,
     *   false if omitted.
     * - dynamicItems - Boolean value indicating whether the item type could generate new menu items.
     *   Optional, false if omitted.
     * - cmsPages - a list of CMS pages (objects of the Cms\Classes\Page class), if the item type requires a CMS page reference to
     *   resolve the item URL.
     * @return array Returns an array
     */
    public function getMenuTypeInfo()
    {
        $arResult = $this->getDefaultMenuTypeInfo();
        if (empty($arResult)) {
            return $arResult;
        }

        $obTheme = Theme::getActiveTheme();
        $obPageList = CmsPage::listInTheme($obTheme, true);

        $arResult['cmsPages'] = $this->filterPageList($obPageList);

        return $arResult;
    }

    /**
     * Get default array for menu type
     * @return array|null
     */
    abstract protected function getDefaultMenuTypeInfo();

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
            if (!$obPage->hasComponent('CategoryPage')) {
                continue;
            }

            /*
             * Component must use a category filter with a routing parameter
             * eg: categoryFilter = "{{ :somevalue }}"
             */
            $arPropertyList = $obPage->getComponentProperties('CategoryPage');
            if (!isset($arPropertyList['slug']) || !preg_match('/{{\s*:/', $arPropertyList['slug'])) {
                continue;
            }
            $arCmsPageList[] = $obPage;
        }

        return $arCmsPageList;
    }

    /**
     * Get subcategories list
     * @return array
     */
    protected function listSubCategoryOptions()
    {
        $arResult = [];
        $obCategoryList = CategoryCollection::make()->tree();
        if ($obCategoryList->isEmpty()) {
            return $arResult;
        }

        $arResult = $this->getCategoryMenuOptions($obCategoryList);

        return $arResult;
    }

    /**
     * Get option array for category
     * @param CategoryCollection|\Lovata\Shopaholic\Classes\Item\CategoryItem[] $obCategoryList
     * @return null|array|string
     */
    protected function getCategoryMenuOptions($obCategoryList)
    {
        if (empty($obCategoryList) || $obCategoryList->isEmpty()) {
            return null;
        }

        $arResult = [];
        foreach ($obCategoryList as $obCategory) {
            if ($obCategory->children->isEmpty()) {
                $arResult[$obCategory->id] = $obCategory->name;
            } else {
                $arResult[$obCategory->id] = [
                    'title' => $obCategory->name,
                    'items' => $this->getCategoryMenuOptions($obCategory->children),
                ];
            }
        }

        return $arResult;
    }

    /**
     * Get array for menu item from category item
     * @param \Lovata\Shopaholic\Classes\Item\CategoryItem $obCategoryItem
     * @param string $sPageCode
     * @param string $sURL
     *
     * @return array
     */
    protected function getCategoryMenuData($obCategoryItem, $sPageCode, $sURL)
    {
        if (empty($obCategoryItem)) {
            return [];
        }

        $arMenuItem = [
            'title'   => $obCategoryItem->name,
            'url'     => $obCategoryItem->getPageUrl($sPageCode),
            'mtime'   => $obCategoryItem->updated_at,
            'viewBag' => ['object' => $obCategoryItem],
        ];

        $arMenuItem['isActive'] = $arMenuItem['url'] == $sURL;

        return $arMenuItem;
    }

    /**
     * Get list of children categories for menu items
     * @param \Lovata\Shopaholic\Classes\Item\CategoryItem $obCategoryItem
     * @param string $sPageCode
     * @param string $sURL
     *
     * @return array
     */
    protected function getChildrenCategoryList($obCategoryItem, $sPageCode, $sURL)
    {
        if (empty($obCategoryItem) || $obCategoryItem->children->isEmpty()) {
            return [];
        }

        $arResult = [];
        foreach ($obCategoryItem->children as $obChildrenCategory) {
            $arMenuItem = $this->getCategoryMenuData($obChildrenCategory, $sPageCode, $sURL);
            if ($obChildrenCategory->children->isNotEmpty()) {
                $arMenuItem['items'] = $this->getChildrenCategoryList($obChildrenCategory, $sPageCode, $sURL);
            }

            $arResult[] = $arMenuItem;
        }

        return $arResult;
    }
}
