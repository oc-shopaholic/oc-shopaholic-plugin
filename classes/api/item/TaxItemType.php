<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use GraphQL\Type\Definition\Type;

use Lovata\Shopaholic\Classes\Item\TaxItem;
use Lovata\Toolbox\Classes\Api\Item\AbstractItemType;
use Lovata\Toolbox\Classes\Api\Type\Custom\Type as CustomType;

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
            'id'               => Type::nonNull(Type::id()),
            'is_global'        => Type::boolean(),
            'name'             => Type::string(),
            'description'      => Type::string(),
            'percent'          => Type::float(),
            'category_id_list' => CustomType::array(),
            'product_id_list'  => CustomType::array(),
            'country_id_list'  => CustomType::array(),
            'state_id_list'    => CustomType::array(),
        ];

        return $arFieldList;
    }
}
