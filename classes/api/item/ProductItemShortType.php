<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use Event;
use Session;
use Illuminate\Support\Arr;

use GraphQL\Type\Definition\Type;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Toolbox\Classes\Api\Item\AbstractItemType;
use Lovata\Toolbox\Classes\Api\Type\Custom\ImageFileType;

/**
 * Class ProductItemShortType
 * @package Lovata\Shopaholic\Classes\Api\Item
 */
class ProductItemShortType extends AbstractItemType
{
    const ITEM_CLASS = ProductItem::class;
    const TYPE_ALIAS = 'productShort';

    /* @var ProductItemShortType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'id'                     => Type::id(),
            'active'                 => Type::boolean(),
            'trashed'                => Type::boolean(),
            'name'                   => Type::string(),
            'slug'                   => Type::string(),
            'code'                   => Type::string(),
            'category_id'            => Type::id(),
            'additional_category_id' => Type::listOf(Type::id()),
            'additional_category'    => [
                'type'    => Type::listOf($this->getRelationType(CategoryItemType::TYPE_ALIAS)),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->additional_category;
                },
            ],
            'brand_id'               => Type::id(),
            'brand'                  => [
                'type'    => $this->getRelationType(BrandItemType::TYPE_ALIAS),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->brand;
                },
            ],
            'preview_text'           => Type::string(),
            'description'            => Type::string(),
            'offer_id_list'          => Type::listOf(Type::id()),
            'category'               => [
                'type'    => $this->getRelationType(CategoryItemType::TYPE_ALIAS),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->category;
                },
            ],
            'preview_image'          => [
                'type'    => $this->getRelationType(ImageFileType::TYPE_ALIAS),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->preview_image;
                },
            ],
            'images'                 => [
                'type'    => Type::listOf($this->getRelationType(ImageFileType::TYPE_ALIAS)),
                'resolve' => function ($obProductItem) {
                    /* @var ProductItem $obProductItem */
                    return $obProductItem->images;
                },
            ],
        ];

        return $arFieldList;
    }

    /**
     * @inheritDoc
     */
    protected function extendResolveMethod($arArgumentList)
    {
        if (!$this->obItem->active) {
            $this->obItem = null;

            return;
        }

        // Set session id to catch viewed products for non-authorized users
        $sSessionId = Arr::get($arArgumentList, 'sessionId');
        if ($sSessionId) {
            Session::setId($sSessionId);
            Session::start();
        }

        // Fire event opening product for PopularityShopaholic plugin
        Event::fire('shopaholic.product.open', [$this->obItem->getObject()]);

        if ($sSessionId) {
            Session::save();
        }
    }

    /**
     * @inheritDoc
     */
    protected function getArguments(): ?array
    {
        $arArgumentList = [
            'sessionId' => [
                'type' => Type::id(),
                'description' => 'User session id. Set the value for non-authorized user to ',
            ],
        ];

        return array_merge(parent::getArguments(), $arArgumentList);
    }

    /**
     * @inheritDoc
     */
    protected function getDescription(): string
    {
        return 'Product short data';
    }
}
