<?php namespace Lovata\Shopaholic\Classes\Item;

use Lovata\Shopaholic\Plugin;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Classes\Collection\CategoryCollection;

use Lovata\Toolbox\Classes\Item\ElementItem;

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
 * @property CategoryItem $parent
 *
 * @property array  $children_id_list
 * @property CategoryCollection $children
 */
class CategoryItem extends ElementItem
{
    const CACHE_TAG_ELEMENT = 'shopaholic-category-element';

    /** @var Category */
    protected $obElement = null;

    public $arRelationList = [
        'parent' => [
            'class' => CategoryItem::class,
            'field' => 'parent_id',
        ],
        'children' => [
            'class' => CategoryCollection::class,
            'field' => 'children_id_list',
        ],
    ];

    /**
     * Set element object
     */
    protected function setElementObject()
    {
        if(!empty($this->obElement) && ! $this->obElement instanceof Category) {
            $this->obElement = null;
        }

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
     * Set element data from model object
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
}