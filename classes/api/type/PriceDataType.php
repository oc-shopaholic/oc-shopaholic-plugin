<?php namespace Lovata\Shopaholic\Classes\Api\Type;

use GraphQL\Type\Definition\Type;

use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Toolbox\Classes\Api\Type\AbstractApiType;

/**
 * Class PriceDataType
 * @package Lovata\Shopaholic\Classes\Api\Type
 */
class PriceDataType extends AbstractApiType
{
    const TYPE_ALIAS = 'price_data';

    /* @var PriceDataType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            // Price fields
            'price' => [
                'type' => Type::string(),
                'description' => 'Formatted price string ("1 200,48")',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->price;
                }
            ],
            'price_value' => [
                'type' => Type::float(),
                'description' => 'Float price value (1200.48)',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->price_value;
                }
            ],
            'price_with_tax' => [
                'type' => Type::string(),
                'description' => 'Formatted price with tax string ("1 200,48")',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->price_with_tax;
                }
            ],
            'price_with_tax_value' => [
                'type' => Type::float(),
                'description' => 'Float price with tax value (1200.48)',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->price_value;
                }
            ],
            'price_without_tax' => [
                'type' => Type::string(),
                'description' => 'Formatted price without tax string ("1 000,40")',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->price_without_tax;
                }
            ],
            'price_without_tax_value' => [
                'type' => Type::float(),
                'description' => 'Float price without tax value (1000.4)',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->price_without_tax_value;
                }
            ],
            'tax_price' => [
                'type' => Type::string(),
                'description' => 'Formatted tax price string ("200,08")',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->tax_price;
                }
            ],
            'tax_price_value' => [
                'type' => Type::float(),
                'description' => 'Float tax price value (200.08)',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->tax_price_value;
                }
            ],
            // Old price fields
            'old_price' => [
                'type' => Type::string(),
                'description' => 'Formatted old price string ("1 680,48")',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->old_price;
                }
            ],
            'old_price_value' => [
                'type' => Type::float(),
                'description' => 'Float old price value (1680.48)',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->old_price_value;
                }
            ],
            'old_price_with_tax' => [
                'type' => Type::string(),
                'description' => 'Formatted old price with tax string ("1 680,48")',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->old_price_with_tax;
                }
            ],
            'old_price_with_tax_value' => [
                'type' => Type::float(),
                'description' => 'Float old price with tax value (1680.48)',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->old_price_with_tax_value;
                }
            ],
            'old_price_without_tax' => [
                'type' => Type::string(),
                'description' => 'Formatted old price without tax string ("1 400,40")',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->old_price_without_tax;
                }
            ],
            'old_price_without_tax_value' => [
                'type' => Type::float(),
                'description' => 'Float old price without tax value (1400.4)',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->old_price_without_tax_value;
                }
            ],
            'tax_old_price' => [
                'type' => Type::string(),
                'description' => 'Formatted tax old price string ("280,08")',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->tax_old_price;
                }
            ],
            'tax_old_price_value' => [
                'type' => Type::float(),
                'description' => 'Float tax old price value (280.08)',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->tax_old_price_value;
                }
            ],
            // Discount price fields
            'discount_price' => [
                'type' => Type::string(),
                'description' => 'Formatted discount price string ("480,00")',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->discount_price;
                }
            ],
            'discount_price_value' => [
                'type' => Type::float(),
                'description' => 'Float discount price value (480)',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->discount_price_value;
                }
            ],
            'discount_price_with_tax' => [
                'type' => Type::string(),
                'description' => 'Formatted discount price with tax string ("480,00")',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->discount_price_with_tax;
                }
            ],
            'discount_price_with_tax_value' => [
                'type' => Type::float(),
                'description' => 'Float discount price with tax value (480)',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->discount_price_with_tax_value;
                }
            ],
            'discount_price_without_tax' => [
                'type' => Type::string(),
                'description' => 'Formatted discount price without tax string ("400,00")',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->discount_price_without_tax;
                }
            ],
            'discount_price_without_tax_value' => [
                'type' => Type::float(),
                'description' => 'Float discount price without tax value (400)',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->discount_price_without_tax_value;
                }
            ],
            'tax_discount_price' => [
                'type' => Type::string(),
                'description' => 'Formatted tax discount price string ("180,00")',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->tax_discount_price;
                }
            ],
            'tax_discount_price_value' => [
                'type' => Type::float(),
                'description' => 'Float tax discount price value (180)',
                'resolve' => function($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->tax_discount_price_value;
                }
            ],
        ];

        return $arFieldList;
    }
}
