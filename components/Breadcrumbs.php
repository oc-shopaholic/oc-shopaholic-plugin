<?php namespace Lovata\Shopaholic\Components;

use System\Classes\PluginManager;
use Cms\Classes\ComponentBase;

use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Product;

/**
 * Class Breadcrumbs
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Breadcrumbs extends ComponentBase
{
    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'          => 'lovata.shopaholic::lang.component.breadcrumbs_name',
            'description'   => 'lovata.shopaholic::lang.component.breadcrumbs_description',
        ];
    }

    /**
     * Get breadcrumbs for category page by category id
     * @param int $iCategoryID
     * @return array
     */
    public function getByCategoryID($iCategoryID)
    {
        if(empty($iCategoryID)) {
            return [];
        }

        $arResult = [];

        //Get category data
        $this->getCategoryData($arResult, $iCategoryID, true);
        $arResult = array_reverse($arResult);
        
        return $arResult;
    }

    /**
     * Get breadcrumbs for tag page by tag ID
     * @param int $iTagID
     * @return array
     */
    public function getByTagID($iTagID)
    {
        //Get tag element
        if(!PluginManager::instance()->hasPlugin('Lovata.TagsShopaholic') || empty($iTagID)) {
            return [];
        }

        $arTagData = \Lovata\TagsShopaholic\Models\Tag::getCacheData($iTagID);
        if(empty($arTagData)) {
            return [];
        }

        $arResult = [];
        $arTagData['active'] = true;
        $arResult[] = $arTagData;

        //Get category data
        $this->getCategoryData($arResult, $arTagData['category_id']);
        $arResult = array_reverse($arResult);

        return $arResult;
    }

    /**
     * Get breadcrumbs by product ID
     * @param int $iProductID
     * @return array
     */
    public function getByProductID($iProductID)
    {
        if(empty($iProductID)) {
            return [];
        }

        //Get product data
        $arProductData = Product::getCacheData($iProductID);
        if(empty($arProductData)) {
            return [];
        }

        $arResult = [];

        //Add product data to list
        $arResult[] = [
            'id'     => $arProductData['id'],
            'name'   => $arProductData['name'],
            'slug'   => $arProductData['slug'],
            'active' => true,
        ];

        //Get category data
        $this->getCategoryData($arResult, $arProductData['category_id']);
        $arResult = array_reverse($arResult);

        return $arResult;
    }

    /**
     * Get category data
     * @param array $arResult
     * @param int $iCategoryID
     * @param bool $bActiveCategory
     */
    protected function getCategoryData(&$arResult, $iCategoryID, $bActiveCategory = false)
    {
        $arCategoryData = Category::getCacheData($iCategoryID);
        if(empty($arCategoryData)) {
            return;
        }

        $arResult[] = [
            'id'     => $arCategoryData['id'],
            'name'   => $arCategoryData['name'],
            'slug'   => $arCategoryData['slug'],
            'active' => $bActiveCategory,
        ];

        if(!empty($arCategoryData['parent_id'])) {
            $this->getCategoryData($arResult, $arCategoryData['parent_id']);
        }
    }
}