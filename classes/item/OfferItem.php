<?php namespace Lovata\Shopaholic\Classes\Item;

use Lovata\Shopaholic\Plugin;
use Lovata\Shopaholic\Models\Offer;

use Lovata\Toolbox\Classes\ElementItem;

/**
 * Class OfferItem
 * @package Lovata\Shopaholic\Classes\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property        $id
 * @property bool   $active
 * @property bool   $trashed
 * @property string $name
 * @property string $code
 * @property int    $product_id
 *
 * @property string $preview_text
 * @property array  $preview_image
 *
 * @property string $description
 * @property array  $images
 *
 * @property string $price
 * @property string $old_price
 * @property float  $price_value
 * @property float  $old_price_value
 *
 * @property int    $quantity
 */
class OfferItem extends ElementItem
{
    const CACHE_TAG_ELEMENT = 'shopaholic-offer-element';

    /** @var Offer */
    protected $obElement = null;

    /**
     * Set element object
     */
    protected function setElementObject()
    {
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
     * Set offer data from model object
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
            'name'            => $this->obElement->name,
            'product_id'      => $this->product_id,
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
     * Set cached offer data
     * Settings:
     *  check_offer_active      - true|false
     *  check_offer_trashed     - true|false
     */
    protected function setCachedData()
    {
        if(empty($this->iElementID)) {
            return;
        }

        //Set model data from cache
        $this->setDataFromCache();
        if(!$this->checkOfferData()) {
            $this->arModelData = [];
            return;
        }

        self::extendMethodResult(__FUNCTION__, $this->arModelData);
    }

    /**
     * Check offer data
     * @return bool
     */
    protected function checkOfferData()
    {
        if(empty($obSettings)) {
            return $this->active && !$this->trashed;
        }

        if($this->obSettings->get('check_offer_active') && !$this->active) {
            return false;
        }

        if($this->obSettings->get('check_offer_trashed') && $this->trashed) {
            return false;
        }

        return true;
    }
}