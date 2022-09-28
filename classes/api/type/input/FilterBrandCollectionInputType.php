<?php namespace Lovata\Shopaholic\Classes\Api\Type\Input;

use GraphQL\Type\Definition\Type;
use Lovata\Toolbox\Classes\Api\Type\Input\FilterCollectionInputType;

/**
 * Class FilterBrandCollectionInputType
 * @package Lovata\Shopaholic\Classes\Api\Type\Input
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class FilterBrandCollectionInputType extends FilterCollectionInputType
{
    const TYPE_ALIAS = 'FilterProductCollectionInput';

    /** @var FilterBrandCollectionInputType */
    protected static $instance;

    protected function getFilterConfig(): array
    {
        $arFieldList = [
            'filterByCategory' => [
                'type' => $this->getRelationType(FilterByCategoryInputType::TYPE_ALIAS),
                'description' => 'Apply filter by category id list',
            ],
        ];

        return $arFieldList;
    }
}
