<?php namespace Lovata\Shopaholic\Classes\Api\Collection;

use Lovata\Shopaholic\Classes\Api\Item\ProductItemType;
use Lovata\Shopaholic\Classes\Api\Type\Enum\ProductCollectionSortingEnumType;
use Lovata\Shopaholic\Classes\Api\Type\Input\FilterProductCollectionInputType;
use Lovata\Shopaholic\Classes\Collection\ProductCollection;
use Lovata\Toolbox\Classes\Api\Collection\AbstractCollectionType;

/**
 * Class ProductCollectionType
 * @package Lovata\Shopaholic\Classes\Api\Collection
 */
class ProductCollectionType extends AbstractCollectionType
{
    const COLLECTION_CLASS = ProductCollection::class;
    const RELATED_ITEM_TYPE_CLASS = ProductItemType::class;

    const TYPE_ALIAS = 'productList';

    /** @var ProductCollectionType */
    protected static $instance;

    /** @var ProductCollection */
    protected $obList;

    protected $sFilterInputTypeClass = FilterProductCollectionInputType::class;
    protected $sSortEnumInputTypeClass = ProductCollectionSortingEnumType::class;

    /**
     * @inheritDoc
     */
    protected function extendResolveMethod($arArgumentList)
    {
        $this->obList->active();
    }

    //
    // Filter methods
    //

    /**
     * Filter by brand ID
     * @param $iBrandId
     * @return void
     */
    protected function filterByBrand($iBrandId)
    {
        $this->obList->brand($iBrandId);
    }

    /**
     * Filter by category ID list
     * @param $arFilterInput
     * @return void
     */
    protected function filterByCategory($arFilterInput)
    {
        $this->obList->category($arFilterInput['categoryIdList'], $arFilterInput['includeChildren']);
    }

    /**
     * Filter by promo block ID
     * @param $iPromoBlockId
     * @return void
     */
    protected function filterByPromoBlock($iPromoBlockId)
    {
        $this->obList->promoBlock($iPromoBlockId);
    }
}
