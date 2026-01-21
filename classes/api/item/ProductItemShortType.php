<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use GraphQL\Type\Definition\Type;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Toolbox\Classes\Api\Item\AbstractItemType;

/**
 * Class ProductItemShortType
 * @package Lovata\Shopaholic\Classes\Api\Item
 */
class ProductItemShortType extends AbstractItemType
{
    const ITEM_CLASS = ProductItem::class;
    const TYPE_ALIAS = 'productShort';

    /* @var ProductItemShortType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'id'                     => Type::id(),
            'active'                 => Type::boolean(),
            'trashed'                => Type::boolean(),
            'name'                   => Type::string(),
            'slug'                   => Type::string(),
            'code'                   => Type::string(),
            'category_id'            => Type::id(),
            'additional_category_id' => Type::listOf(Type::id()),
            'additional_category'    => [
                'type'    => Type::listOf($this->getRelationType(CategoryItemType::TYPE_ALIAS)),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->additional_category;
                },
            ],
            'brand_id'               => Type::id(),
            'brand'                  => [
                'type'    => $this->getRelationType(BrandItemType::TYPE_ALIAS),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->brand;
                },
            ],
            'preview_text'           => Type::string(),
            'description'            => Type::string(),
            'offer_id_list'          => Type::listOf(Type::id()),
            'category'               => [
                'type'    => $this->getRelationType(CategoryItemType::TYPE_ALIAS),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->category;
                },
            ],
        ];

        $arPreviewImageFields = $this->getAttachOneFileFields('preview_image');
        $arImagesFields = $this->getAttachManyFileFields('images');
        $arFieldList = array_merge($arFieldList, $arPreviewImageFields, $arImagesFields);

        return $arFieldList;
    }
}
