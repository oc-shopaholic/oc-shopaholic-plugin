<?php namespace Lovata\Shopaholic\Classes\Api\Collection;

use GraphQL\Type\Definition\Type;

use Lovata\Shopaholic\Classes\Api\Item\BrandItemType;
use Lovata\Shopaholic\Classes\Collection\BrandCollection;

use Lovata\Toolbox\Classes\Api\Collection\AbstractCollectionType;
use Lovata\Toolbox\Classes\Api\Type\TypeFactory;

/**
 * Class BrandCollectionType
 * @package Lovata\Shopaholic\Classes\Api\Collection
 */
class BrandCollectionType extends AbstractCollectionType
{
    const COLLECTION_CLASS = BrandCollection::class;
    const TYPE_ALIAS = 'brandList';

    /** @var BrandCollectionType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = parent::getFieldList();
        $arFieldList['list'] = Type::listOf(TypeFactory::instance()->get(BrandItemType::TYPE_ALIAS));
        $arFieldList['item'] = TypeFactory::instance()->get(BrandItemType::TYPE_ALIAS);
        $arFieldList['id'] = Type::id();

        return $arFieldList;
    }

    /**
     * Get config for "args" attribute
     * @return array|null
     */
    protected function getArguments(): ?array
    {
        $arArgumentList = parent::getArguments();
        $arArgumentList['category'] = Type::id();

        return $arArgumentList;
    }
}
