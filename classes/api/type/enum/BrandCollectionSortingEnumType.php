<?php namespace Lovata\Shopaholic\Classes\Api\Type\Enum;

use Lovata\Toolbox\Classes\Api\Type\Enum\AbstractEnumType;

/**
 * Class BrandCollectionSortingEnum
 * @package Lovata\Shopaholic\Classes\Api\Type\Enum
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class BrandCollectionSortingEnumType extends AbstractEnumType
{
    const TYPE_ALIAS = 'BrandListSortingEnum';

    const SORT_ORDER = 'sort_order';

    /** @var BrandCollectionSortingEnumType */
    protected static $instance;

    /**
     * @inheritDoc
     */
    protected function getValueList(): array
    {
        $arValueList = [
            'SORT_ORDER' => [
                'value' => self::SORT_ORDER,
                'description' => 'Sorting by sort order',
            ],
        ];

        return $arValueList;
    }
}
