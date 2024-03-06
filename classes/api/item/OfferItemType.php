<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use GraphQL\Type\Definition\Type;

use Lovata\Shopaholic\Classes\Api\Type\PriceDataType;
use Lovata\Shopaholic\Classes\Item\OfferItem;

use Lovata\Toolbox\Classes\Api\Item\AbstractItemType;

/**
 * Class OfferItemType
 * @package Lovata\Shopaholic\Classes\Api\Item
 */
class OfferItemType extends AbstractItemType
{
    const ITEM_CLASS = OfferItem::class;
    const TYPE_ALIAS = 'offer';

    /* @var OfferItemType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'id'                 => Type::id(),
            'active'             => Type::boolean(),
            'trashed'            => Type::boolean(),
            'name'               => Type::string(),
            'code'               => Type::string(),
            'product_id'         => Type::id(),
            'product'            => [
                'type'    => $this->getRelationType(ProductItemShortType::TYPE_ALIAS),
                'resolve' => function ($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->product;
                },
            ],
            'weight'             => Type::float(),
            'height'             => Type::float(),
            'length'             => Type::float(),
            'width'              => Type::float(),
            'quantity_in_unit'   => Type::float(),
            'measure_id'         => Type::id(),
            'measure'            => [
                'type'    => $this->getRelationType(MeasureItemType::TYPE_ALIAS),
                'resolve' => function ($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->measure;
                },
            ],
            'measure_of_unit_id' => Type::id(),
            'measure_of_unit'    => [
                'type'    => $this->getRelationType(MeasureItemType::TYPE_ALIAS),
                'resolve' => function ($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->measure_of_unit;
                },
            ],
            'dimensions_measure' => [
                'type'    => $this->getRelationType(MeasureItemType::TYPE_ALIAS),
                'resolve' => function ($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->dimensions_measure;
                },
            ],
            'weight_measure'     => [
                'type'    => $this->getRelationType(MeasureItemType::TYPE_ALIAS),
                'resolve' => function ($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->weight_measure;
                },
            ],
            'preview_text'       => Type::string(),
            'description'        => Type::string(),
            'price_data'         => [
                'type'    => $this->getRelationType(PriceDataType::TYPE_ALIAS),
                'resolve' => function ($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem;
                },
            ],
            'currency'           => [
                'type'    => Type::string(),
                'resolve' => function ($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->currency;
                },
            ],
            'currency_code'      => [
                'type'    => Type::string(),
                'resolve' => function ($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->currency_code;
                },
            ],
            'tax_percent'        => [
                'type'    => Type::float(),
                'resolve' => function ($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->tax_percent;
                },
            ],
            'tax_list'           => [
                'type'    => Type::listOf($this->getRelationType(TaxItemType::TYPE_ALIAS)),
                'resolve' => function ($obOfferItem) {
                    /** @var OfferItem $obOfferItem */
                    return $obOfferItem->tax_list;
                },
            ],
            'quantity'           => Type::int(),
        ];

        $arPreviewImageFields = $this->getAttachOneFileFields('preview_image');
        $arImagesFields = $this->getAttachManyFileFields('images');
        $arFieldList = array_merge($arFieldList, $arPreviewImageFields, $arImagesFields);

        return $arFieldList;
    }

    /**
     * Get config for "args" attribute
     * @return array|null
     */
    protected function getArguments(): ?array
    {
        $arArgumentList = parent::getArguments();
        $arArgumentList['setActiveCurrency'] = Type::string();
        $arArgumentList['setActivePriceType'] = Type::int();

        return $arArgumentList;
    }
}
