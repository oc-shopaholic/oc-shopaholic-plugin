<?php namespace Lovata\Shopaholic\Classes\Api\Collection;

use GraphQL\Type\Definition\Type;

use Lovata\Shopaholic\Classes\Api\Item\PromoBlockItemType;
use Lovata\Shopaholic\Classes\Collection\PromoBlockCollection;

use Lovata\Toolbox\Classes\Api\Collection\AbstractCollectionType;
use Lovata\Toolbox\Classes\Api\Type\TypeFactory;

/**
 * Class PromoBlockCollectionType
 * @package Lovata\Shopaholic\Classes\Api\Collection
 */
class PromoBlockCollectionType extends AbstractCollectionType
{
    const COLLECTION_CLASS = PromoBlockCollection::class;
    const TYPE_ALIAS = 'promo_block_list';

    /** @var PromoBlockCollectionType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = parent::getFieldList();
        $arFieldList['list'] = Type::listOf(TypeFactory::instance()->get(PromoBlockItemType::TYPE_ALIAS));
        $arFieldList['item'] = TypeFactory::instance()->get(PromoBlockItemType::TYPE_ALIAS);
        $arFieldList['id'] = Type::int();

        return $arFieldList;
    }
}
