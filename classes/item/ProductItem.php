<?php namespace Lovata\Shopaholic\Classes\Item;

use Lovata\Shopaholic\Plugin;
use Lovata\Shopaholic\Models\Product;

use Lovata\Toolbox\Classes\ElementItem;

/**
 * Class ProductItem
 * @package Lovata\Shopaholic\Classes\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property        $id
 * @property bool   $active
 * @property bool   $trashed
 * @property string $name
 * @property string $slug
 * @property string $code
 * @property int    $category_id
 * @property int    $brand_id
 *
 * @property string $preview_text
 * @property array  $preview_image
 *
 * @property string $description
 * @property array  $images
 *
 * @property array  $offer_id_list
 * @property OfferItem[] $offer
 *
 * Popularity for Shopaholic field
 * @property int $popularity
 */
class ProductItem extends ElementItem
{
    const CACHE_TAG_ELEMENT = 'shopaholic-product-element';

    /** @var Product */
    protected $obElement = null;

    /**
     * Set element object
     */
    protected function setElementObject()
    {
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
     * Set product data from model object
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
            'offer_id_list' => $this->obElement->offers->lists('id'),
        ];

        return $arResult;
    }

    /**
     * Set cached product data
     * Settings:
     *  check_product_active    - true|false
     *  check_product_trashed   - true|false
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
        if(!$this->checkProductData()) {
            $this->arModelData = [];
            return;
        }

        $this->setOfferList();
        self::extendMethodResult(__FUNCTION__, $this->arModelData);
    }

    /**
     * Set offer list data from cache
     */
    protected function setOfferList()
    {
        $this->arModelData['offer'] = [];
        $arOfferIDList = $this->offer_id_list;

        if(empty($arOfferIDList)) {
            return;
        }

        foreach($arOfferIDList as $iOfferID) {

            $obOfferStore = OfferItem::make($iOfferID, null, $this->obSettings);
            if($obOfferStore->isEmpty()) {
                continue;
            }

            $this->arModelData['offer'][$iOfferID] = $obOfferStore;
        }
    }

    /**
     * Check product data
     * @return bool
     */
    protected function checkProductData()
    {
        if(empty($obSettings)) {
            return $this->active && !$this->trashed;
        }

        if($this->obSettings->get('check_product_active') && !$this->active) {
            return false;
        }

        if($this->obSettings->get('check_product_trashed') && $this->trashed) {
            return false;
        }

        return true;
    }
}