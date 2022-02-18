<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use GraphQL\Type\Definition\Type;

use Lovata\Shopaholic\Classes\Item\CategoryItem;

use Lovata\Toolbox\Classes\Api\Item\AbstractItemType;
use Lovata\Toolbox\Classes\Api\Type\Custom\Type as CustomType;

/**
 * Class CategoryItemSimpleType
 * @package Lovata\Shopaholic\Classes\Api\Item
 */
class CategoryItemSimpleType extends AbstractItemType
{
    const ITEM_CLASS = CategoryItem::class;
    const TYPE_ALIAS = 'category_simple';

    /* @var CategoryItemSimpleType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'id'                    => Type::int(),
            'name'                  => Type::string(),
            'slug'                  => Type::string(),
            'code'                  => Type::string(),
            'nest_depth'            => Type::int(),
            'parent_id'             => Type::int(),
            'product_count'         => Type::int(),
            'preview_text'          => Type::string(),
            'preview_image_url'         => [
                'type'    => Type::string(),
                'resolve' => function ($obCategoryItem) {
                    /* @var CategoryItem $obCategoryItem */
                    return $obCategoryItem->preview_image->getPath();
                }
            ],
            'preview_image_title'       => [
                'type'    => Type::string(),
                'resolve' => function ($obCategoryItem) {
                    /* @var CategoryItem $obCategoryItem */
                    return data_get($obCategoryItem->preview_image, 'attributes.title');
                },
            ],
            'preview_image_description' => [
                'type'    => Type::string(),
                'resolve' => function ($obCategoryItem) {
                    /* @var CategoryItem $obCategoryItem */
                    return data_get($obCategoryItem->preview_image, 'attributes.description');
                },
            ],
            'preview_image_file_name'   => [
                'type'    => Type::string(),
                'resolve' => function ($obCategoryItem) {
                    /* @var CategoryItem $obCategoryItem */
                    return data_get($obCategoryItem->preview_image, 'attributes.file_name');
                },
            ],
            'icon_url'                  => [
                'type'    => Type::string(),
                'resolve' => function ($obCategoryItem) {
                    /* @var CategoryItem $obCategoryItem */
                    return $obCategoryItem->icon->getPath();
                }
            ],
            'icon_title'                => [
                'type'    => Type::string(),
                'resolve' => function ($obCategoryItem) {
                    /* @var CategoryItem $obCategoryItem */
                    return data_get($obCategoryItem->icon, 'attributes.title');
                },
            ],
            'icon_description'          => [
                'type'    => Type::string(),
                'resolve' => function ($obCategoryItem) {
                    /* @var CategoryItem $obCategoryItem */
                    return data_get($obCategoryItem->icon, 'attributes.description');
                },
            ],
            'icon_file_name'            => [
                'type'    => Type::string(),
                'resolve' => function ($obCategoryItem) {
                    /* @var CategoryItem $obCategoryItem */
                    return data_get($obCategoryItem->icon, 'attributes.file_name');
                },
            ],
            'description'           => Type::string(),
            'images'                => [
                'type'    => CustomType::array(),
                'resolve' => function ($obCategoryItem) {
                    return $this->getImageList($obCategoryItem, 'images');
                },
            ],
            'updated_at'            => Type::string(),
            'children_id_list'      => CustomType::array(),
        ];

        return $arFieldList;
    }
}
