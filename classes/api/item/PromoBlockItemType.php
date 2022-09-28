<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use GraphQL\Type\Definition\Type;

use Lovata\Shopaholic\Classes\Item\PromoBlockItem;
use Lovata\Toolbox\Classes\Api\Item\AbstractItemType;
use Lovata\Toolbox\Classes\Api\Type\Custom\ImageFileType;

/**
 * Class PromoBlockItemType
 * @package Lovata\Shopaholic\Classes\Api\Item
 */
class PromoBlockItemType extends AbstractItemType
{
    const ITEM_CLASS = PromoBlockItem::class;
    const TYPE_ALIAS = 'promoBlock';

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
            'id'           => Type::id(),
            'name'         => Type::string(),
            'slug'         => Type::string(),
            'code'         => Type::string(),
            'type'         => Type::string(),
            'date_begin'   => Type::string(),
            'date_end'     => Type::string(),
            'preview_text' => Type::string(),
            'description'  => Type::string(),
            'product'      => [
                'type'    => Type::listOf($this->getRelationType(ProductItemType::TYPE_ALIAS)),
                'resolve' => function ($obPromoBlockItem) {
                    /** @var PromoBlockItem $obPromoBlockItem */
                    return $obPromoBlockItem->product;
                },
            ],
            'preview_image'    => [
                'type'    => $this->getRelationType(ImageFileType::TYPE_ALIAS),
                'resolve' => function ($obPromoBlockItem) {
                    /* @var PromoBlockItem $obPromoBlockItem */
                    return $obPromoBlockItem->preview_image;
                },
            ],
            'icon'             => [
                'type'    => $this->getRelationType(ImageFileType::TYPE_ALIAS),
                'resolve' => function ($obPromoBlockItem) {
                    /* @var PromoBlockItem $obPromoBlockItem */
                    return $obPromoBlockItem->icon;
                },
            ],
            'images'           => [
                'type'    => Type::listOf($this->getRelationType(ImageFileType::TYPE_ALIAS)),
                'resolve' => function ($obPromoBlockItem) {
                    /* @var PromoBlockItem $obPromoBlockItem */
                    return $obPromoBlockItem->images;
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
