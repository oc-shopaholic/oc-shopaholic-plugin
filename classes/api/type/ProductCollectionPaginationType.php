<?php namespace Lovata\Shopaholic\Classes\Api\Type;

use GraphQL\Type\Definition\Type;
use Lovata\Shopaholic\Classes\Api\Item\ProductItemType;
use Lovata\Toolbox\Classes\Api\Type\AbstractObjectType;
use Lovata\Toolbox\Classes\Api\Type\Custom\PaginationInfoType;

/**
 * Class ProductCollectionPaginationType
 * @package Lovata\Shopaholic\Classes\Api\Type
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class ProductCollectionPaginationType extends AbstractObjectType
{
    const TYPE_ALIAS = 'ProductCollectionPaginationType';

    /** @var ProductCollectionPaginationType */
    protected static $instance;

    /**
     * @inheritDoc
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'items' => Type::nonNull(Type::listOf($this->getRelationType(ProductItemType::TYPE_ALIAS))),
            'pageInfo' => Type::nonNull($this->getRelationType(PaginationInfoType::TYPE_ALIAS)),
        ];

        return $arFieldList;
    }
}
