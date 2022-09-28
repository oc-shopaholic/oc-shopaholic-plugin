<?php namespace Lovata\Shopaholic\Classes\Api;

use Lovata\Toolbox\Classes\Api\Type\FrontendTypeFactory;
/** Item types */
use Lovata\Shopaholic\Classes\Api\Item\BrandItemType;
use Lovata\Shopaholic\Classes\Api\Item\CategoryItemShortType;
use Lovata\Shopaholic\Classes\Api\Item\CategoryItemType;
use Lovata\Shopaholic\Classes\Api\Item\CurrencyItemType;
use Lovata\Shopaholic\Classes\Api\Item\MeasureItemType;
use Lovata\Shopaholic\Classes\Api\Item\OfferItemType;
use Lovata\Shopaholic\Classes\Api\Item\ProductItemShortType;
use Lovata\Shopaholic\Classes\Api\Item\ProductItemType;
use Lovata\Shopaholic\Classes\Api\Item\PromoBlockItemType;
use Lovata\Shopaholic\Classes\Api\Item\TaxItemType;
/** Collection types */
use Lovata\Shopaholic\Classes\Api\Collection\BrandCollectionType;
use Lovata\Shopaholic\Classes\Api\Collection\CategoryCollectionType;
use Lovata\Shopaholic\Classes\Api\Collection\CurrencyCollectionType;
use Lovata\Shopaholic\Classes\Api\Collection\OfferCollectionType;
use Lovata\Shopaholic\Classes\Api\Collection\ProductCollectionType;
use Lovata\Shopaholic\Classes\Api\Collection\PromoBlockCollectionType;
use Lovata\Shopaholic\Classes\Api\Collection\TaxCollectionType;
/** Query types */
use Lovata\Shopaholic\Classes\Api\Query\CurrencyGetActiveQueryType;
/** Mutation types */
use Lovata\Shopaholic\Classes\Api\Mutation\CurrencySwitchActiveMutationType;
/** Enum types */
use Lovata\Shopaholic\Classes\Api\Type\Enum\BrandCollectionSortingEnumType;
use Lovata\Shopaholic\Classes\Api\Type\Enum\ProductCollectionSortingEnumType;
/** Input types */
use Lovata\Shopaholic\Classes\Api\Type\Input\CurrencySwitchActiveInputType;
use Lovata\Shopaholic\Classes\Api\Type\Input\FilterByCategoryInputType;
use Lovata\Shopaholic\Classes\Api\Type\Input\FilterProductCollectionInputType;
/** Payload types */
use Lovata\Shopaholic\Classes\Api\Type\Payload\CurrencySwitchActivePayloadType;
/** Custom types */
use Lovata\Shopaholic\Classes\Api\Type\PriceDataType;
use Lovata\Shopaholic\Classes\Api\Type\ProductCollectionPaginationType;

/**
 * ExtendFrontendTypeClassList
 * @package Lovata\Shopaholic\Classes\Api
 */
class ExtendFrontendTypeClassList
{
    /**
     * Add listeners
     */
    public function subscribe()
    {
        FrontendTypeFactory::extend(function (FrontendTypeFactory $obTypeFactory) {
            $arQueryClassList = [
                /** Query types */
                CurrencyGetActiveQueryType::class,
                /** Item types */
                BrandItemType::class,
                CategoryItemShortType::class,
                CategoryItemType::class,
                CurrencyItemType::class,
                MeasureItemType::class,
                OfferItemType::class,
                ProductItemShortType::class,
                ProductItemType::class,
                PromoBlockItemType::class,
                TaxItemType::class,
                /** Collection types */
                BrandCollectionType::class,
                CategoryCollectionType::class,
                CurrencyCollectionType::class,
                OfferCollectionType::class,
                PromoBlockCollectionType::class,
                ProductCollectionType::class,
                TaxCollectionType::class,
                /** Mutation types */
                CurrencySwitchActiveMutationType::class,
            ];

            $arTypeClassList = [
                PriceDataType::class,
                /** Item types */
                BrandItemType::class,
                CategoryItemShortType::class,
                CategoryItemType::class,
                CurrencyItemType::class,
                MeasureItemType::class,
                OfferItemType::class,
                ProductItemShortType::class,
                ProductItemType::class,
                PromoBlockItemType::class,
                TaxItemType::class,
                /** Collection types */
                BrandCollectionType::class,
                CategoryCollectionType::class,
                CurrencyCollectionType::class,
                OfferCollectionType::class,
                PromoBlockCollectionType::class,
                ProductCollectionType::class,
                TaxCollectionType::class,
                /** Enum types */
                BrandCollectionSortingEnumType::class,
                ProductCollectionSortingEnumType::class,
                /** Input types */
                CurrencySwitchActiveInputType::class,
                FilterProductCollectionInputType::class,
                FilterByCategoryInputType::class,
                /** Payload types */
                CurrencySwitchActivePayloadType::class,
                /** Types */
                ProductCollectionPaginationType::class,
            ];

            $obTypeFactory->addQueryClass($arQueryClassList);
            $obTypeFactory->addTypeClass($arTypeClassList);
        });
    }
}
