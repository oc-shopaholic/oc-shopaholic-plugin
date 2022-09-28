<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use GraphQL\Type\Definition\Type;
use Lovata\Shopaholic\Classes\Item\CurrencyItem;
use Lovata\Toolbox\Classes\Api\Item\AbstractItemType;

/**
 * Class CurrencyItemType
 * @package Lovata\Shopaholic\Classes\Api\Item
 */
class CurrencyItemType extends AbstractItemType
{
    const ITEM_CLASS = CurrencyItem::class;
    const TYPE_ALIAS = 'currency';

    /* @var CurrencyItemType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'id'         => Type::id(),
            'is_default' => Type::boolean(),
            'name'       => Type::string(),
            'code'       => Type::string(),
            'symbol'     => Type::string(),
            'rate'       => Type::float(),
        ];

        return $arFieldList;
    }

    /**
     * @inheritDoc
     */
    protected function extendResolveMethod($arArgumentList)
    {
        if (!$this->obItem->active) {
            $this->obItem = null;
        }
    }
}
