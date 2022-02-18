<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use GraphQL\Type\Definition\Type;

use Lovata\Shopaholic\Classes\Item\PromoBlockItem;
use Lovata\Toolbox\Classes\Api\Item\AbstractItemType;
use Lovata\Toolbox\Classes\Api\Type\Custom\Type as CustomType;

/**
 * Class PromoBlockItemType
 * @package Lovata\Shopaholic\Classes\Api\Item
 */
class PromoBlockItemType extends AbstractItemType
{
    const ITEM_CLASS = PromoBlockItem::class;
    const TYPE_ALIAS = 'promo_block';

    /* @var CurrencyItemType */
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
            'name'                      => Type::string(),
            'slug'                      => Type::string(),
            'code'                      => Type::string(),
            'type'                      => Type::string(),
            'date_begin'                => Type::string(),
            'date_end'                  => Type::string(),
            'preview_text'              => Type::string(),
            'preview_image_url'         => [
                'type'    => Type::string(),
                'resolve' => function ($obPromoBlockItem) {
                    /* @var PromoBlockItem $obPromoBlockItem */
                    return $obPromoBlockItem->preview_image->getPath();
                }
            ],
            'preview_image_title'       => [
                'type'    => Type::string(),
                'resolve' => function ($obPromoBlockItem) {
                    /* @var PromoBlockItem $obPromoBlockItem */
                    return data_get($obPromoBlockItem->preview_image, 'attributes.title');
                },
            ],
            'preview_image_description' => [
                'type'    => Type::string(),
                'resolve' => function ($obPromoBlockItem) {
                    /* @var PromoBlockItem $obPromoBlockItem */
                    return data_get($obPromoBlockItem->preview_image, 'attributes.description');
                },
            ],
            'preview_image_file_name'   => [
                'type'    => Type::string(),
                'resolve' => function ($obPromoBlockItem) {
                    /* @var PromoBlockItem $obPromoBlockItem */
                    return data_get($obPromoBlockItem->icon, 'attributes.file_name');
                },
            ],
            'icon_url'                  => [
                'type'    => Type::string(),
                'resolve' => function ($obPromoBlockItem) {
                    /* @var PromoBlockItem $obPromoBlockItem */
                    return $obPromoBlockItem->icon->getPath();
                }
            ],
            'icon_title'                => [
                'type'    => Type::string(),
                'resolve' => function ($obPromoBlockItem) {
                    /* @var PromoBlockItem $obPromoBlockItem */
                    return data_get($obPromoBlockItem->icon, 'attributes.title');
                },
            ],
            'icon_description'          => [
                'type'    => Type::string(),
                'resolve' => function ($obPromoBlockItem) {
                    /* @var PromoBlockItem $obPromoBlockItem */
                    return data_get($obPromoBlockItem->icon, 'attributes.description');
                },
            ],
            'icon_file_name'            => [
                'type'    => Type::string(),
                'resolve' => function ($obPromoBlockItem) {
                    /* @var PromoBlockItem $obPromoBlockItem */
                    return data_get($obPromoBlockItem->icon, 'attributes.file_name');
                },
            ],
            'description'               => Type::string(),
            'images'                    => [
                'type'    => CustomType::array(),
                'resolve' => function ($obPromoBlockItem) {
                    return $this->getImageList($obPromoBlockItem, 'images');
                },
            ],
            'product'                   => [
                'type'    => Type::listOf($this->getRelationType(ProductItemType::TYPE_ALIAS)),
                'resolve' => function ($obPromoBlockItem) {
                    /** @var PromoBlockItem $obPromoBlockItem */
                    return $obPromoBlockItem->product;
                },
            ],
        ];

        return $arFieldList;
    }
}
