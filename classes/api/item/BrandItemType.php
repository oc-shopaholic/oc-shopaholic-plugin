<?php namespace Lovata\Shopaholic\Classes\Api\Item;

use GraphQL\Type\Definition\Type;
use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Toolbox\Classes\Api\Item\AbstractItemType;

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
            'id'           => Type::id(),
            'active'       => Type::boolean(),
            'name'         => Type::string(),
            'slug'         => Type::string(),
            'code'         => Type::string(),
            'preview_text' => Type::string(),
            'description'  => Type::string(),
        ];

        $arPreviewImageFields = $this->getAttachOneFileFields('preview_image');
        $arIconFields = $this->getAttachOneFileFields('icon');
        $arImagesFields = $this->getAttachManyFileFields('images');
        $arFieldList = array_merge($arFieldList, $arPreviewImageFields, $arIconFields, $arImagesFields);

        return $arFieldList;
    }
}
