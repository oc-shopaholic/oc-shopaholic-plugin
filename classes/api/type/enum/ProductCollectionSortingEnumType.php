<?php namespace Lovata\Shopaholic\Classes\Api\Type\Enum;

use Lovata\Shopaholic\Classes\Store\ProductListStore;
use Lovata\Toolbox\Classes\Api\Type\Enum\AbstractEnumType;

/**
 * Class ProductCollectionSortingEnumType
 * @package Lovata\Shopaholic\Classes\Api\Type\Enum
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class ProductCollectionSortingEnumType extends AbstractEnumType
{
    const TYPE_ALIAS = 'ProductListSortingEnum';

    /** @var ProductCollectionSortingEnumType */
    protected static $instance;

    /**
     * @inheritDoc
     */
    protected function getValueList(): array
    {
        $arValueList = [
            'SORT_NO' => [
                'value' => ProductListStore::SORT_NO,
                'description' => 'Without sorting',
            ],
            'SORT_NEW' => [
                'value' => ProductListStore::SORT_NEW,
                'description' => 'Sort by new products',
            ],
            'SORT_PRICE_ASC' => [
                'value' => ProductListStore::SORT_PRICE_ASC,
                'description' => 'Sort by ascending price',
            ],
            'SORT_PRICE_DESC' => [
                'value' => ProductListStore::SORT_PRICE_DESC,
                'description' => 'Sort by descending price',
            ],
        ];

        return $arValueList;
    }

    /**
     * @inheritDoc
     */
    protected function getDescription(): string
    {
        return 'Enums for sorting product list';
    }
}
