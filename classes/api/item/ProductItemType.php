<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use GraphQL\Type\Definition\Type;

use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Toolbox\Classes\Api\Item\AbstractItemType;
use Lovata\Toolbox\Classes\Api\Type\Custom\Type as CustomType;

/**
 * Class ProductItemType
 * @package Lovata\Shopaholic\Classes\Api\Item
 */
class ProductItemType extends AbstractItemType
{
    const ITEM_CLASS = ProductItem::class;
    const TYPE_ALIAS = 'product';

    /* @var ProductItemType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'id'                        => Type::int(),
            'active'                    => Type::boolean(),
            'trashed'                   => Type::boolean(),
            'name'                      => Type::string(),
            'slug'                      => Type::string(),
            'code'                      => Type::string(),
            'category_id'               => Type::int(),
            'additional_category_id'    => CustomType::array(),
            'additional_category'       => [
                'type'    => Type::listOf($this->getRelationType(CategoryItemType::TYPE_ALIAS)),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->additional_category;
                },
            ],
            'brand_id'                  => Type::int(),
            'brand'                     => [
                'type'    => $this->getRelationType(BrandItemType::TYPE_ALIAS),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->brand;
                },
            ],
            'preview_text'              => Type::string(),
            'preview_image_url'         => [
                'type'    => Type::string(),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->preview_image->getPath();
                }
            ],
            'preview_image_title'       => [
                'type'    => Type::string(),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return data_get($obProductItem->preview_image, 'attributes.title');
                },
            ],
            'preview_image_description' => [
                'type'    => Type::string(),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return data_get($obProductItem->preview_image, 'attributes.description');
                },
            ],
            'preview_image_file_name'   => [
                'type'    => Type::string(),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return data_get($obProductItem->preview_image, 'attributes.file_name');
                },
            ],
            'description'               => Type::string(),
            'images'                    => [
                'type'    => CustomType::array(),
                'resolve' => function ($obProductItem) {
                    return $this->getImageList($obProductItem, 'images');
                },
            ],
            'offer_id_list'             => CustomType::array(),
            'offer'                     => [
                'type'    => Type::listOf($this->getRelationType(OfferItemType::TYPE_ALIAS)),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->offer;
                },
            ],
            'category'                  => [
                'type'    => $this->getRelationType(CategoryItemType::TYPE_ALIAS),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->category;
                },
            ],
        ];

        return $arFieldList;
    }
}
