<?php namespace Lovata\Shopaholic\Classes\Api\Type\Input;

use GraphQL\Type\Definition\Type;
use Lovata\Toolbox\Classes\Api\Type\Input\AbstractInputType;
use Lovata\Toolbox\Classes\Api\Type\Input\FilterCollectionInputType;

/**
 * Class FilterByCategoryInputType
 * @package Lovata\Shopaholic\Classes\Api\Type\Input
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class FilterByCategoryInputType extends AbstractInputType
{
    const TYPE_ALIAS = 'FilterByCategoryInput';

    /** @var FilterByCategoryInputType */
    protected static $instance;

    /**
     * @inheritDoc
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'categoryIdList' => [
                'type' => Type::listOf(Type::id()),
                'description' => '',
            ],
            'includeChildren' => [
                'type' => Type::boolean(),
                'description' => '',
                'defaultValue' => false,
            ],
        ];

        return $arFieldList;
    }
}
