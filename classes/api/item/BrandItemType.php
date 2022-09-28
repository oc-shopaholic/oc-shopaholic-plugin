<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use GraphQL\Type\Definition\Type;
use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Toolbox\Classes\Api\Item\AbstractItemType;
use Lovata\Toolbox\Classes\Api\Type\Custom\ImageFileType;

/**
 * Class BrandItemType
 * @package Lovata\Shopaholic\Classes\Api\Item
 */
class BrandItemType extends AbstractItemType
{
    const ITEM_CLASS = BrandItem::class;
    const TYPE_ALIAS = 'brand';

    /* @var BrandItemType */
    protected static $instance;

    /**
     * Get type fields
     * @return array
     * @throws \GraphQL\Error\Error
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'id'            => Type::id(),
            'active'        => Type::boolean(),
            'name'          => Type::string(),
            'slug'          => Type::string(),
            'code'          => Type::string(),
            'preview_text'  => Type::string(),
            'description'   => Type::string(),
            'preview_image' => [
                'type'    => $this->getRelationType(ImageFileType::TYPE_ALIAS),
                'resolve' => function ($obBrandItem) {
                    /* @var BrandItem $obBrandItem */
                    return $obBrandItem->preview_image;
                },
            ],
            'icon'          => [
                'type'    => $this->getRelationType(ImageFileType::TYPE_ALIAS),
                'resolve' => function ($obBrandItem) {
                    /* @var BrandItem $obBrandItem */
                    return $obBrandItem->icon;
                },
            ],
            'images'        => [
                'type'    => Type::listOf($this->getRelationType(ImageFileType::TYPE_ALIAS)),
                'resolve' => function ($obBrandItem) {
                    /* @var BrandItem $obBrandItem */
                    return $obBrandItem->images;
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
        }
    }
}
