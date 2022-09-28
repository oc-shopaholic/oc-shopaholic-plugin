<?php namespace Lovata\Shopaholic\Classes\Api\Type\Input;

use GraphQL\Type\Definition\Type;
use Lovata\Toolbox\Classes\Api\Type\Input\FilterCollectionInputType;

/**
 * Class FilterProductCollectionInputType
 * @package Lovata\Shopaholic\Classes\Api\Type\Input
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class FilterProductCollectionInputType extends FilterCollectionInputType
{
    const TYPE_ALIAS = 'FilterProductCollectionInput';

    /** @var FilterProductCollectionInputType */
    protected static $instance;

    protected function getFilterConfig(): array
    {
        $arFieldList = [
            'filterByBrand' => [
                'type' => Type::id(),
                'description' => 'Apply filter by brand id'
            ],
            'filterByCategory' => [
                'type' => $this->getRelationType(FilterByCategoryInputType::TYPE_ALIAS),
                'description' => 'Apply filter by category id list',
            ],
            'filterByPromoBlock' => [
                'type' => Type::id(),
                'description' => 'Apply filter by promo block id'
            ],
        ];

        return $arFieldList;
    }
}
