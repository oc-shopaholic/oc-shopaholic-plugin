<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use GraphQL\Type\Definition\Type;
use Lovata\Shopaholic\Classes\Item\CategoryItem;

/**
 * Class CategoryItemType
 * @package Lovata\Shopaholic\Classes\Api\Item
 */
class CategoryItemType extends CategoryItemShortType
{
    const TYPE_ALIAS = 'category';

    /* @var CategoryItemType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arParentFieldList = parent::getFieldList();

        $arExtendedFieldList = [
            'children' => [
                'type'    => Type::listOf($this->getRelationType(CategoryItemShortType::TYPE_ALIAS)),
                'resolve' => function ($obCategoryItem) {
                    /* @var CategoryItem $obCategoryItem */
                    return $obCategoryItem->children;
                },
            ],
            'parent'   => [
                'type'    => $this->getRelationType(CategoryItemShortType::TYPE_ALIAS),
                'resolve' => function ($obCategoryItem) {
                    /* @var CategoryItem $obCategoryItem */
                    return $obCategoryItem->parent;
                },
            ],
        ];

        $arFieldList = array_merge($arParentFieldList, $arExtendedFieldList);

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
