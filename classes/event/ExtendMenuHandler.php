<?php namespace Lovata\Shopaholic\Classes\Event;

use Lovata\Shopaholic\Classes\Helper\AllCategoriesMenuType;
use Lovata\Shopaholic\Classes\Helper\CatalogMenuType;
use Lovata\Shopaholic\Classes\Helper\CategoryMenuType;

/**
 * Class ExtendMenuHandler
 * @package Lovata\Shopaholic\Classes\Event
 *
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * @author Alvaro Cánepa, https://github.com/alvaro-canepa
 */
class ExtendMenuHandler
{
    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        /*
         * Register menu items for the RainLab.Pages plugin
         */
        $obEvent->listen('pages.menuitem.listTypes', function () {

            $arResult = [
                CatalogMenuType::MENU_TYPE       => 'lovata.shopaholic::lang.menu.shop_catalog',
                CategoryMenuType::MENU_TYPE      => 'lovata.shopaholic::lang.menu.shop_category',
                AllCategoriesMenuType::MENU_TYPE => 'lovata.shopaholic::lang.menu.all_shop_categories',
            ];

            return $arResult;
        });

        $obEvent->listen('pages.menuitem.getTypeInfo', function ($sType) {

            $obMenuType = $this->getMenuTypeObject($sType);
            if (!empty($obMenuType)) {
                return $obMenuType->getMenuTypeInfo();
            }
        });

        $obEvent->listen('pages.menuitem.resolveItem', function ($sType, $obItem, $sURL) {

            $obMenuType = $this->getMenuTypeObject($sType);
            if (!empty($obMenuType)) {
                return $obMenuType->resolveMenuItem($obItem, $sURL);
            }
        });
    }

    /**
     * Get new menu object by type value
     * @param string $sType
     * @return \Lovata\Shopaholic\Classes\Helper\CommonMenuType
     */
    protected function getMenuTypeObject($sType)
    {
        switch ($sType) {
            case CategoryMenuType::MENU_TYPE:
                return new CategoryMenuType();
            case CatalogMenuType::MENU_TYPE:
                return new  CatalogMenuType();
            case AllCategoriesMenuType::MENU_TYPE:
                return new AllCategoriesMenuType();
            default:
                return null;
        }
    }
}
