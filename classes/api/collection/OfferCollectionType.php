<?php namespace Lovata\Shopaholic\Classes\Api\Collection;

use GraphQL\Type\Definition\Type;

use Lovata\Shopaholic\Classes\Api\Item\OfferItemType;
use Lovata\Shopaholic\Classes\Collection\OfferCollection;

use Lovata\Toolbox\Classes\Api\Collection\AbstractCollectionType;
use Lovata\Toolbox\Classes\Api\Type\TypeFactory;

/**
 * Class OfferCollectionType
 * @package Lovata\Shopaholic\Classes\Api\Collection
 */
class OfferCollectionType extends AbstractCollectionType
{
    const COLLECTION_CLASS = OfferCollection::class;
    const TYPE_ALIAS = 'offer_list';

    /** @var OfferCollectionType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = parent::getFieldList();
        $arFieldList['list'] = Type::listOf(TypeFactory::instance()->get(OfferItemType::TYPE_ALIAS));
        $arFieldList['item'] = TypeFactory::instance()->get(OfferItemType::TYPE_ALIAS);
        $arFieldList['id'] = Type::int();

        return $arFieldList;
    }

    /**
     * Get config for "args" attribute
     * @return array|null
     */
    protected function getArguments(): ?array
    {
        $arArgumentList = parent::getArguments();
        $arArgumentList['priceTypeId'] = Type::int();
        $arArgumentList['sort'] = Type::string();

        return $arArgumentList;
    }
}
