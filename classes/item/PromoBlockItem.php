<?php namespace Lovata\Shopaholic\Classes\Item;

use Event;
use Lovata\Toolbox\Classes\Item\ElementItem;

use Lovata\Shopaholic\Models\PromoBlock;
use Lovata\Shopaholic\Classes\Store\ProductListStore;
use Lovata\Shopaholic\Classes\Collection\ProductCollection;

/**
 * Class PromoBlockItem
 * @package Lovata\PromoBlocksShopaholic\Classes\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property int                       $id
 * @property string                    $name
 * @property string                    $slug
 * @property string                    $code
 * @property string                    $type
 * @property string $
 * @property \October\Rain\Argon\Argon $date_begin
 * @property \October\Rain\Argon\Argon $date_end
 *
 * @property string                    $preview_text
 * @property \System\Models\File       $preview_image
 *
 * @property string                    $description
 * @property \October\Rain\Database\Collection|\System\Models\File[]  $images
 * @property ProductCollection|\Lovata\Shopaholic\Classes\Item\ProductItem[] $product
 */
class PromoBlockItem extends ElementItem
{
    const MODEL_CLASS = PromoBlock::class;
    
    /** @var PromoBlock */
    protected $obElement = null;

    /**
     * Get product collection attribute
     * @return ProductCollection
     */
    protected function getProductAttribute() : ProductCollection
    {
        $obProductList = $this->getAttribute('product');
        if (!empty($obProductList) && $obProductList instanceof ProductCollection) {
            return $obProductList;
        }

        $arPromoProductIDList = ProductListStore::instance()->promo->get($this->id);
        $arPromoProductIDList = array_merge($arPromoProductIDList, $this->getAdditionalProductIDList());
        $arPromoProductIDList = array_unique($arPromoProductIDList);

        $obProductList = ProductCollection::make()->intersect($arPromoProductIDList);
        $this->setAttribute('product', $obProductList);

        return $obProductList;
    }

    /**
     * Get additional product ID list attached to promo block
     * @return array
     */
    protected function getAdditionalProductIDList() : array
    {
        $arResult = [];

        //Fire event, get additional product ID list
        $arEventDataList = Event::fire(PromoBlock::EVENT_GET_PRODUCT_LIST, $this);
        if (empty($arEventDataList)) {
            return $arResult;
        }

        //Process event data
        foreach ($arEventDataList as $arProductIDList) {
            if (empty($arProductIDList) || !is_array($arProductIDList)) {
                continue;
            }

            $arResult = array_merge($arResult, $arProductIDList);
        }

        $arResult = array_unique($arResult);

        return $arResult;
    }
}
