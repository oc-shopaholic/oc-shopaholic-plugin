<?php namespace Lovata\Shopaholic\Classes\Item;

use Lovata\Toolbox\Classes\Item\ElementItem;
use Lovata\Toolbox\Traits\Item\TraitCheckItemActive;
use Lovata\Toolbox\Traits\Item\TraitCheckItemTrashed;

use Lovata\Shopaholic\Models\Settings;
use Lovata\Shopaholic\Plugin;
use Lovata\Shopaholic\Models\Offer;


/**
 * Class OfferItem
 * @package Lovata\Shopaholic\Classes\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property             $id
 * @property bool        $active
 * @property bool        $trashed
 * @property string      $name
 * @property string      $code
 * @property int         $product_id
 * @property ProductItem $product
 *
 * @property string      $preview_text
 * @property array       $preview_image
 *
 * @property string      $description
 * @property array       $images
 *
 * @property string      $price
 * @property string      $old_price
 * @property float       $price_value
 * @property float       $old_price_value
 * @property string      $currency
 * 
 * @property int         $quantity
 *
 * Stores for Shopaholic
 * @property int $store_id
 * @property \Lovata\StoresShopaholic\Classes\Item\StoreItem $store
 */
class OfferItem extends ElementItem
{
    use TraitCheckItemActive;
    use TraitCheckItemTrashed;

    const CACHE_TAG_ELEMENT = 'shopaholic-offer-element';

    /** @var Offer */
    protected $obElement = null;

    public $arRelationList = [
        'product' => [
            'class' => ProductItem::class,
            'field' => 'product_id',
        ],
    ];

    /**
     * Set element object
     */
    protected function setElementObject()
    {
        if(!empty($this->obElement) && ! $this->obElement instanceof Offer) {
            $this->obElement = null;
        }

        if(!empty($this->obElement) || empty($this->iElementID)) {
            return;
        }

        $this->obElement = Offer::withTrashed()->find($this->iElementID);
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
            'id'              => $this->obElement->id,
            'active'          => $this->obElement->active,
            'trashed'         => $this->obElement->trashed(),
            'product_id'      => $this->obElement->product_id,
            'name'            => $this->obElement->name,
            'code'            => $this->obElement->code,
            'preview_text'    => $this->obElement->preview_text,
            'preview_image'   => $this->obElement->getFileData('preview_image'),
            'description'     => $this->obElement->description,
            'images'          => $this->obElement->getFileListData('images'),
            'price'           => $this->obElement->price,
            'old_price'       => $this->obElement->old_price,
            'price_value'     => $this->obElement->getPriceValue(),
            'old_price_value' => $this->obElement->getOldPriceValue(),
            'quantity'        => $this->obElement->quantity,
        ];

        return $arResult;
    }

    /**
     * Get currency value
     * @return null|string
     */
    protected function getCurrencyAttribute()
    {
        return Settings::getValue('currency');
    }
}