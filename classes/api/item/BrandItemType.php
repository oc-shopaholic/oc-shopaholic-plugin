<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use GraphQL\Type\Definition\Type;

use Lovata\Shopaholic\Classes\Item\BrandItem;

use Lovata\Toolbox\Classes\Api\Item\AbstractItemType;
use Lovata\Toolbox\Classes\Api\Type\Custom\Type as CustomType;

/**
 * Class BrandItemType
 * @package Lovata\Shopaholic\Classes\Api\Item
 */
class BrandItemType extends AbstractItemType
{
    const ITEM_CLASS = BrandItem::class;
    const TYPE_ALIAS = 'brand';

    /* @var BrandItemType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'id'            => Type::int(),
            'active'        => Type::boolean(),
            'name'          => Type::string(),
            'slug'          => Type::string(),
            'code'          => Type::string(),
            'preview_text'  => Type::string(),
            'preview_image_url'         => [
                'type'    => Type::string(),
                'resolve' => function ($obBrandItem) {
                    /* @var BrandItem $obBrandItem */
                    return $obBrandItem->preview_image->getPath();
                }
            ],
            'preview_image_title'       => [
                'type'    => Type::string(),
                'resolve' => function ($obBrandItem) {
                    /* @var BrandItem $obBrandItem */
                    return data_get($obBrandItem->preview_image, 'attributes.title');
                },
            ],
            'preview_image_description' => [
                'type'    => Type::string(),
                'resolve' => function ($obBrandItem) {
                    /* @var BrandItem $obBrandItem */
                    return data_get($obBrandItem->preview_image, 'attributes.description');
                },
            ],
            'preview_image_file_name'   => [
                'type'    => Type::string(),
                'resolve' => function ($obBrandItem) {
                    /* @var BrandItem $obBrandItem */
                    return data_get($obBrandItem->preview_image, 'attributes.file_name');
                },
            ],
            'icon_url'                  => [
                'type'    => Type::string(),
                'resolve' => function ($obBrandItem) {
                    /* @var BrandItem $obBrandItem */
                    return $obBrandItem->icon->getPath();
                }
            ],
            'icon_title'                => [
                'type'    => Type::string(),
                'resolve' => function ($obBrandItem) {
                    /* @var BrandItem $obBrandItem */
                    return data_get($obBrandItem->icon, 'attributes.title');
                },
            ],
            'icon_description'          => [
                'type'    => Type::string(),
                'resolve' => function ($obBrandItem) {
                    /* @var BrandItem $obBrandItem */
                    return data_get($obBrandItem->icon, 'attributes.description');
                },
            ],
            'icon_file_name'            => [
                'type'    => Type::string(),
                'resolve' => function ($obBrandItem) {
                    /* @var BrandItem $obBrandItem */
                    return data_get($obBrandItem->icon, 'attributes.file_name');
                },
            ],
            'images'        => [
                'type'    => CustomType::array(),
                'resolve' => function ($obBrandItem) {
                    return $this->getImageList($obBrandItem, 'images');
                },
            ],
            'description'   => Type::string(),
        ];

        return $arFieldList;
    }
}
