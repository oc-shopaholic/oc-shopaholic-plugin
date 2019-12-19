<?php namespace Lovata\Shopaholic\Components;

use Cms\Classes\ComponentBase;
use System\Classes\PluginManager;

use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Item\ProductItem;

/**
 * Class Breadcrumbs
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * @deprecated
 */
class Breadcrumbs extends ComponentBase
{
    protected $arResult = [];

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'lovata.shopaholic::lang.component.breadcrumbs_name',
            'description' => 'lovata.shopaholic::lang.component.breadcrumbs_description',
        ];
    }

    /**
     * Get breadcrumbs for category page by category id
     * @param int $iCategoryID
     * @return array
     */
    public function getByCategoryID($iCategoryID)
    {
        if (empty($iCategoryID)) {
            return [];
        }

        //Get category data
        $this->addCategoryData($iCategoryID, true);
        $this->arResult = array_reverse($this->arResult);

        return $this->arResult;
    }

    /**
     * Get breadcrumbs for tag page by tag ID
     * @param int $iTagID
     * @return array
     */
    public function getByTagID($iTagID)
    {
        //Get tag element
        if (!PluginManager::instance()->hasPlugin('Lovata.TagsShopaholic') || empty($iTagID)) {
            return $this->arResult;
        }

        $obTagItem = \Lovata\TagsShopaholic\Classes\Item\TagItem::make($iTagID);
        if ($obTagItem->isEmpty()) {
            return $this->arResult;
        }

        $arTagData = $obTagItem->toArray();
        $arTagData['active'] = true;
        $arTagData['item'] = $obTagItem;

        $this->arResult[] = $arTagData;

        //Get category data
        $this->addCategoryData($obTagItem->category_id);
        $this->arResult = array_reverse($this->arResult);

        return $this->arResult;
    }

    /**
     * Get breadcrumbs by product ID
     * @param int $iProductID
     * @return array
     */
    public function getByProductID($iProductID)
    {
        if (empty($iProductID)) {
            return $this->arResult;
        }

        //Get product data
        $obProductItem = ProductItem::make($iProductID);
        if ($obProductItem->isEmpty()) {
            return $this->arResult;
        }

        //Add product data to list
        $this->arResult[] = [
            'id'     => $obProductItem->id,
            'name'   => $obProductItem->name,
            'slug'   => $obProductItem->slug,
            'active' => true,
            'item'   => $obProductItem,
        ];

        //Get category data
        $this->addCategoryData($obProductItem->category_id);
        $this->arResult = array_reverse($this->arResult);

        return $this->arResult;
    }

    /**
     * Add category data
     * @param int  $iCategoryID
     * @param bool $bActiveCategory
     */
    protected function addCategoryData($iCategoryID, $bActiveCategory = false)
    {
        $obCategoryItem = CategoryItem::make($iCategoryID);
        if ($obCategoryItem->isEmpty()) {
            return;
        }

        $this->arResult[] = [
            'id'     => $obCategoryItem->id,
            'name'   => $obCategoryItem->name,
            'slug'   => $obCategoryItem->slug,
            'active' => $bActiveCategory,
            'item'   => $obCategoryItem,
        ];

        if (!empty($obCategoryItem->parent_id)) {
            $this->addCategoryData($obCategoryItem->parent_id);
        }
    }
}
