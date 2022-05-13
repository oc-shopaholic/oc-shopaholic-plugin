<?php namespace Lovata\Shopaholic\Classes\Api\Collection;

use GraphQL\Type\Definition\Type;

use Lovata\Shopaholic\Classes\Api\Item\TaxItemType;
use Lovata\Shopaholic\Classes\Collection\TaxCollection;

use Lovata\Toolbox\Classes\Api\Collection\AbstractCollectionType;
use Lovata\Toolbox\Classes\Api\Type\TypeFactory;

/**
 * Class TaxCollectionType
 * @package Lovata\Shopaholic\Classes\Api\Collection
 */
class TaxCollectionType extends AbstractCollectionType
{
    const COLLECTION_CLASS = TaxCollection::class;
    const TYPE_ALIAS = 'taxList';

    /** @var TaxCollectionType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = parent::getFieldList();
        $arFieldList['list'] = Type::listOf(TypeFactory::instance()->get(TaxItemType::TYPE_ALIAS));
        $arFieldList['item'] = TypeFactory::instance()->get(TaxItemType::TYPE_ALIAS);
        $arFieldList['id'] = Type::id();

        return $arFieldList;
    }
}
