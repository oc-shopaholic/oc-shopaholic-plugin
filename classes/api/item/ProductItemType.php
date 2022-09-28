<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Lovata\Shopaholic\Classes\Item\ProductItem;

/**
 * Class ProductItemType
 * @package Lovata\Shopaholic\Classes\Api\Item
 */
class ProductItemType extends ProductItemShortType
{
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
        $arParentFieldList = parent::getFieldList();

        $arExtendedFieldList = [
            'offer' => [
                'type'    => Type::listOf($this->getRelationType(OfferItemType::TYPE_ALIAS)),
                'resolve' => function ($obProductItem, $arArgumentList, $sContext, ResolveInfo $obResolveInfo) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->offer;
                },
            ],
        ];

        $arFieldList = array_merge($arParentFieldList, $arExtendedFieldList);

        return $arFieldList;
    }

    /**
     * @inheritDoc
     */
    protected function getDescription(): string
    {
        return 'Product data';
    }
}
