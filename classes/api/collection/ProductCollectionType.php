<?php namespace Lovata\Shopaholic\Classes\Api\Collection;

use Illuminate\Support\Arr;

use GraphQL\Type\Definition\Type;

use Lovata\Shopaholic\Classes\Api\Item\OfferItemType;
use Lovata\Shopaholic\Classes\Api\Item\ProductItemType;
use Lovata\Shopaholic\Classes\Collection\ProductCollection;

use Lovata\Toolbox\Classes\Api\Collection\AbstractCollectionType;
use Lovata\Toolbox\Classes\Api\Type\Custom\Type as CustomType;
use Lovata\Toolbox\Classes\Api\Type\TypeFactory;

/**
 * Class ProductCollectionType
 * @package Lovata\Shopaholic\Classes\Api\Collection
 */
class ProductCollectionType extends AbstractCollectionType
{
    const COLLECTION_CLASS = ProductCollection::class;
    const TYPE_ALIAS = 'productList';

    /** @var ProductCollectionType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = parent::getFieldList();
        $arFieldList['list'] = Type::listOf(TypeFactory::instance()->get(ProductItemType::TYPE_ALIAS));
        $arFieldList['item'] = TypeFactory::instance()->get(OfferItemType::TYPE_ALIAS);
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
        $arArgumentList['brand'] = Type::id();
        $arArgumentList['categoryList'] = CustomType::array();
        $arArgumentList['categoryWithChildren'] = Type::boolean();
        $arArgumentList['priceTypeId'] = Type::id();
        $arArgumentList['getOfferMaxPrice'] = Type::string();
        $arArgumentList['getOfferMinPrice'] = Type::string();
        $arArgumentList['promo'] = Type::id();
        $arArgumentList['promoBlock'] = Type::id();
        $arArgumentList['sort'] = Type::string();

        return $arArgumentList;
    }

    protected function getCategoryParam($arArgumentList)
    {
        $arResult = Arr::get($arArgumentList, 'categoryList');
        $bWithChildren = (boolean) Arr::get($arArgumentList, 'categoryWithChildren');

        if ($bWithChildren) {
            $arResult[] = true;
        }

        return $arResult;
    }
}
