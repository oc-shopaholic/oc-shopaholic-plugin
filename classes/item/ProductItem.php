<?php namespace Lovata\Shopaholic\Classes\Item;

use Lovata\Shopaholic\Plugin;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Collection\OfferCollection;

use Lovata\Toolbox\Classes\Item\ElementItem;
use Lovata\Toolbox\Traits\Item\TraitCheckItemActive;
use Lovata\Toolbox\Traits\Item\TraitCheckItemTrashed;

/**
 * Class ProductItem
 * @package Lovata\Shopaholic\Classes\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property                 $id
 * @property bool            $active
 * @property bool            $trashed
 * @property string          $name
 * @property string          $slug
 * @property string          $code
 *
 * @property int             $category_id
 * @property CategoryItem    $category
 *
 * @property int             $brand_id
 * @property BrandItem       $brand
 *
 * @property string          $preview_text
 * @property array           $preview_image
 *
 * @property string          $description
 * @property array           $images
 *
 * @property array           $offer_id_list
 * @property OfferCollection $offer
 *
 * Popularity for Shopaholic field
 * @property int             $popularity
 *
 * Stickers for Shopaholic field
 * @property array $sticker_id_list
 * @property \Lovata\StickersShopaholic\Classes\Collection\StickerCollection $sticker
 *
 * Sets for Shopaholic field
 * @property array $set_id_list
 * @property \Lovata\SetsShopaholic\Classes\Collection\SetCollection $set
 */
class ProductItem extends ElementItem
{
    use TraitCheckItemActive;
    use TraitCheckItemTrashed;

    const CACHE_TAG_ELEMENT = 'shopaholic-product-element';

    /** @var Product */
    protected $obElement = null;

    public $arRelationList = [
        'offer' => [
            'class' => OfferCollection::class,
            'field' => 'offer_id_list',
        ],
        'category' => [
            'class' => CategoryItem::class,
            'field' => 'category_id',
        ],
        'brand' => [
            'class' => BrandItem::class,
            'field' => 'brand_id',
        ],
    ];

    /**
     * Set element object
     */
    protected function setElementObject()
    {
        if(!empty($this->obElement) && ! $this->obElement instanceof Product) {
            $this->obElement = null;
        }

        if(!empty($this->obElement) || empty($this->iElementID)) {
            return;
        }

        $this->obElement = Product::withTrashed()->find($this->iElementID);
    }

    /**
     * Get cache tag array for model
     * @return array
     */
    protected static function getCacheTag()
    {
        return [Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT];
    }

    /**
     * Set element data from model object
     *
     * @return array
     */
    protected function getElementData()
    {
        if(empty($this->obElement)) {
            return null;
        }

        $arResult = [
            'id'            => $this->obElement->id,
            'active'        => $this->obElement->active,
            'trashed'       => $this->obElement->trashed(),
            'name'          => $this->obElement->name,
            'slug'          => $this->obElement->slug,
            'code'          => $this->obElement->code,
            'category_id'   => $this->obElement->category_id,
            'brand_id'      => $this->obElement->brand_id,
            'preview_text'  => $this->obElement->preview_text,
            'preview_image' => $this->obElement->getFileData('preview_image'),
            'description'   => $this->obElement->description,
            'images'        => $this->obElement->getFileListData('images'),
            'offer_id_list' => $this->obElement->offer()->withTrashed()->lists('id'),
        ];

        return $arResult;
    }
}