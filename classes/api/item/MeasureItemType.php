<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use GraphQL\Type\Definition\Type;
use Lovata\Shopaholic\Classes\Item\MeasureItem;
use Lovata\Toolbox\Classes\Api\Item\AbstractItemType;

/**
 * Class MeasureItemType
 * @package Lovata\Shopaholic\Classes\Api\Item
 */
class MeasureItemType extends AbstractItemType
{
    const ITEM_CLASS = MeasureItem::class;
    const TYPE_ALIAS = 'measure';

    /* @var MeasureItemType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'id'   => Type::id(),
            'name' => Type::string(),
            'code' => Type::string(),
        ];

        return $arFieldList;
    }
}
