<?php namespace Lovata\Shopaholic\Classes\Api\Collection;

use GraphQL\Type\Definition\Type;

use Lovata\Shopaholic\Classes\Api\Item\CategoryItemType;
use Lovata\Shopaholic\Classes\Collection\CategoryCollection;

use Lovata\Toolbox\Classes\Api\Collection\AbstractCollectionType;
use Lovata\Toolbox\Classes\Api\Type\TypeFactory;

/**
 * Class CategoryCollectionType
 * @package Lovata\Shopaholic\Classes\Api\Collection
 */
class CategoryCollectionType extends AbstractCollectionType
{
    const COLLECTION_CLASS = CategoryCollection::class;
    const RELATED_ITEM_TYPE_CLASS = CategoryItemType::class;
    const TYPE_ALIAS = 'categoryList';

    /** @var CategoryCollectionType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = parent::getFieldList();
        $arFieldList['list'] = Type::listOf(TypeFactory::instance()->get(CategoryItemType::TYPE_ALIAS));
        $arFieldList['item'] = TypeFactory::instance()->get(CategoryItemType::TYPE_ALIAS);
        $arFieldList['id'] = Type::id();

        return $arFieldList;
    }

    /**
     * @inheritDoc
     */
    protected function extendResolveMethod($arArgumentList)
    {
        $this->obList = $this->obList->active();
    }
}
