<?php namespace Lovata\Shopaholic\Classes\Api\Collection;

use GraphQL\Type\Definition\Type;

use Lovata\Shopaholic\Classes\Api\Item\CurrencyItemType;
use Lovata\Shopaholic\Classes\Collection\CurrencyCollection;

use Lovata\Toolbox\Classes\Api\Collection\AbstractCollectionType;
use Lovata\Toolbox\Classes\Api\Type\TypeFactory;

/**
 * Class CurrencyCollectionType
 * @package Lovata\Shopaholic\Classes\Api\Collection
 */
class CurrencyCollectionType extends AbstractCollectionType
{
    const COLLECTION_CLASS = CurrencyCollection::class;
    const TYPE_ALIAS = 'currencyList';

    /** @var CurrencyCollectionType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = parent::getFieldList();
        $arFieldList['list'] = Type::listOf(TypeFactory::instance()->get(CurrencyItemType::TYPE_ALIAS));
        $arFieldList['item'] = TypeFactory::instance()->get(CurrencyItemType::TYPE_ALIAS);
        $arFieldList['id'] = Type::id();

        return $arFieldList;
    }
}
