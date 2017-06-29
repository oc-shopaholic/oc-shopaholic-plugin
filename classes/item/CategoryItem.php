<?php namespace Lovata\Shopaholic\Classes\Item;

use Lovata\Shopaholic\Plugin;
use Lovata\Shopaholic\Models\Category;

use Lovata\Toolbox\Classes\ElementItem;

/**
 * Class CategoryItem
 * @package Lovata\Shopaholic\Classes\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property        $id
 * @property string $name
 * @property string $slug
 * @property string $code
 * @property int    $nest_depth
 * @property int    $parent_id
 *
 * @property string $preview_text
 * @property array  $preview_image
 *
 * @property string $description
 * @property array  $images
 *
 * @property array  $children_id_list
 * @property CategoryItem[] $children
 */
class CategoryItem extends ElementItem
{
    const CACHE_TAG_ELEMENT = 'shopaholic-category-element';

    /** @var Category */
    protected $obElement = null;

    /**
     * Set element object
     */
    protected function setElementObject()
    {
        if(!empty($this->obElement) || empty($this->iElementID)) {
            return;
        }

        $this->obElement = Category::active()->find($this->iElementID);
    }

    /**
     * Get cache tag array for model
     * @return array
     */
    protected static function getCacheTag()
    {
        return [Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT];
    }

    /**
     * Set product data from model object
     *
     * @return array
     */
    protected function getElementData()
    {
        if(empty($this->obElement)) {
            return null;
        }

        $arResult = [
            'id'            => $this->obElement->id,
            'name'          => $this->obElement->name,
            'slug'          => $this->obElement->slug,
            'code'          => $this->obElement->code,
            'preview_text'  => $this->obElement->preview_text,
            'description'   => $this->obElement->description,
            'nest_depth'    => $this->obElement->getDepth(),
            'parent_id'     => $this->obElement->parent_id,
            'preview_image' => $this->obElement->getFileData('preview_image'),
            'images'        => $this->obElement->getFileListData('images'),
        ];

        $arResult['children_id_list'] = $this->obElement->children()
            ->active()
            ->orderBy('nest_left', 'asc')
            ->lists('id');

        return $arResult;
    }

    /**
     * Set cached category data
     */
    protected function setCachedData()
    {
        if(empty($this->iElementID)) {
            return;
        }

        //Set model data from cache
        $this->setDataFromCache();

        $this->setChildrenCategoryList();
        self::extendMethodResult(__FUNCTION__, $this->arModelData);
    }

    /**
     * Set children category list
     */
    protected function setChildrenCategoryList()
    {
        //Add data children categories
        $this->arModelData['children'] = [];
        $arChildrenCategoryIDList = $this->children_id_list;

        if(empty($arChildrenCategoryIDList)) {
            return;
        }

        foreach($arChildrenCategoryIDList as $iChildCategoryID) {

            $arChildCategoryData = self::make($iChildCategoryID);
            if($arChildCategoryData->isEmpty()) {
                continue;
            }

            $arResult['children'][$iChildCategoryID] = $arChildCategoryData;
        }
    }
}