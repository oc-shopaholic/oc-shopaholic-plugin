<?php namespace Lovata\Shopaholic\Classes\Api\Collection;

use Lovata\Shopaholic\Classes\Api\Item\BrandItemType;
use Lovata\Shopaholic\Classes\Api\Type\Enum\BrandCollectionSortingEnumType;
use Lovata\Shopaholic\Classes\Api\Type\Input\FilterBrandCollectionInputType;
use Lovata\Shopaholic\Classes\Collection\BrandCollection;

use Lovata\Toolbox\Classes\Api\Collection\AbstractCollectionType;

/**
 * Class BrandCollectionType
 * @package Lovata\Shopaholic\Classes\Api\Collection
 */
class BrandCollectionType extends AbstractCollectionType
{
    const COLLECTION_CLASS = BrandCollection::class;
    const RELATED_ITEM_TYPE_CLASS = BrandItemType::class;

    const TYPE_ALIAS = 'brandList';

    /** @var BrandCollectionType */
    protected static $instance;

    protected $sSortEnumInputTypeClass = BrandCollectionSortingEnumType::class;
    protected $sFilterInputTypeClass = FilterBrandCollectionInputType::class;

    /**
     * @inheritDoc
     */
    protected function extendResolveMethod($arArgumentList)
    {
        $this->obList = $this->obList->active();
    }

    //
    // Filter methods
    //

    /**
     * Filter by category ID list
     * @param $arFilterInput
     * @return void
     */
    protected function filterByCategory($arFilterInput)
    {
        $this->obList->category($arFilterInput['categoryIdList'], $arFilterInput['includeChildren']);
    }
}
