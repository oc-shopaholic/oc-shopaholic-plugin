<?php namespace Lovata\Shopaholic\Classes\Item;

use Lovata\Toolbox\Classes\Item\ElementItem;
use Lovata\Toolbox\Traits\Helpers\PriceHelperTrait;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Classes\Helper\CurrencyHelper;

/**
 * Class OfferItem
 * @package Lovata\Shopaholic\Classes\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @see \Lovata\Shopaholic\Tests\Unit\Item\OfferItemTest
 * @link https://github.com/lovata/oc-shopaholic-plugin/wiki/OfferItem
 *
 * @property int         $id
 * @property bool        $active
 * @property bool        $trashed
 * @property string      $name
 * @property string      $code
 * @property int         $product_id
 * @property ProductItem $product
 *
 * @property string      $preview_text
 * @property \System\Models\File $preview_image
 *
 * @property string      $description
 * @property \October\Rain\Database\Collection|\System\Models\File[]  $images
 *
 * @property string      $price
 * @property string      $old_price
 * @property float       $price_value
 * @property float       $old_price_value
 * @property string      $currency
 *
 * @property int         $quantity
 *
 * Properties for Shopaholic
 * @see \Lovata\PropertiesShopaholic\Classes\Event\OfferModelHandler::extendOfferItem
 * @property array       $property_value_array
 * @property \Lovata\PropertiesShopaholic\Classes\Collection\PropertyCollection|\Lovata\PropertiesShopaholic\Classes\Item\PropertyItem[] $property
 *
 * Discounts for Shopaholic
 * @property string $discount_price
 * @property float  $discount_price_value
 * @property int    $discount_id
 * @property float  $discount_value
 * @property string $discount_type
 */
class OfferItem extends ElementItem
{
    use PriceHelperTrait;

    const MODEL_CLASS = Offer::class;

    /** @var Offer */
    protected $obElement = null;

    public $arRelationList = [
        'product' => [
            'class' => ProductItem::class,
            'field' => 'product_id',
        ],
    ];

    public $arPriceField = ['price', 'old_price'];

    /**
     * Check element, active == true, trashed == false
     * @return bool
     */
    public function isActive()
    {
        return $this->active && !$this->trashed;
    }

    /**
     * Set element object
     */
    protected function setElementObject()
    {
        $this->obElement = Offer::withTrashed()->find($this->iElementID);
    }

    /**
     * Get currency value
     * @return null|string
     */
    protected function getCurrencyAttribute()
    {
        return CurrencyHelper::instance()->getActive();
    }

    /**
     * Set element data from model object
     *
     * @return array
     */
    protected function getElementData()
    {
        $arResult = [
            'trashed'       => $this->obElement->trashed(),
        ];

        return $arResult;
    }
}
