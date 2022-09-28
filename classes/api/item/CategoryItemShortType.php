<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use GraphQL\Type\Definition\Type;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Toolbox\Classes\Api\Item\AbstractItemType;
use Lovata\Toolbox\Classes\Api\Type\Custom\ImageFileType;

/**
 * Class CategoryItemShortType
 * @package Lovata\Shopaholic\Classes\Api\Item
 */
class CategoryItemShortType extends AbstractItemType
{
    const ITEM_CLASS = CategoryItem::class;
    const TYPE_ALIAS = 'categoryShort';

    /* @var CategoryItemShortType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'id'               => Type::id(),
            'name'             => Type::string(),
            'slug'             => Type::string(),
            'code'             => Type::string(),
            'nest_depth'       => Type::int(),
            'parent_id'        => Type::id(),
            'product_count'    => Type::int(),
            'preview_text'     => Type::string(),
            'description'      => Type::string(),
            'updated_at'       => Type::string(),
            'children_id_list' => Type::listOf(Type::id()),
            'preview_image'    => [
                'type'    => $this->getRelationType(ImageFileType::TYPE_ALIAS),
                'resolve' => function ($obCategoryItem) {
                    /* @var CategoryItem $obCategoryItem */
                    return $obCategoryItem->preview_image;
                },
            ],
            'icon'             => [
                'type'    => $this->getRelationType(ImageFileType::TYPE_ALIAS),
                'resolve' => function ($obCategoryItem) {
                    /* @var CategoryItem $obCategoryItem */
                    return $obCategoryItem->icon;
                },
            ],
            'images'           => [
                'type'    => Type::listOf($this->getRelationType(ImageFileType::TYPE_ALIAS)),
                'resolve' => function ($obCategoryItem) {
                    /* @var CategoryItem $obCategoryItem */
                    return $obCategoryItem->images;
                },
            ],
        ];

        return $arFieldList;
    }

    /**
     * @inheritDoc
     */
    protected function extendResolveMethod($arArgumentList)
    {
        if (!$this->obItem->active) {
            $this->obItem = null;
        }
    }
}
