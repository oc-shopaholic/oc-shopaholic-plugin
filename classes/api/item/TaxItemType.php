<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use GraphQL\Type\Definition\Type;
use Lovata\Shopaholic\Classes\Item\TaxItem;
use Lovata\Toolbox\Classes\Api\Item\AbstractItemType;

/**
 * Class TaxItemType
 * @package Lovata\Shopaholic\Classes\Api\Item
 */
class TaxItemType extends AbstractItemType
{
    const ITEM_CLASS = TaxItem::class;
    const TYPE_ALIAS = 'tax';

    /* @var TaxItemType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'id'               => Type::id(),
            'is_global'        => Type::boolean(),
            'name'             => Type::string(),
            'description'      => Type::string(),
            'percent'          => Type::float(),
            'category_id_list' => Type::listOf(Type::id()),
            'product_id_list'  => Type::listOf(Type::id()),
            'country_id_list'  => Type::listOf(Type::id()),
            'state_id_list'    => Type::listOf(Type::id()),
        ];

        return $arFieldList;
    }
}
