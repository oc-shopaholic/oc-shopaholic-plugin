<?php namespace Lovata\Shopaholic\Classes\Item;

use Lovata\Toolbox\Classes\Item\ElementItem;
use Lovata\Toolbox\Traits\Helpers\PriceHelperTrait;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Classes\Helper\CurrencyHelper;

/**
 * Class OfferItem
 * @package Lovata\Shopaholic\Classes\Item
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property int                                                                                                                         $id
 * @property bool                                                                                                                        $active
 * @property bool                                                                                                                        $trashed
 * @property string                                                                                                                      $name
 * @property string                                                                                                                      $code
 * @property int                                                                                                                         $product_id
 * @property ProductItem                                                                                                                 $product
 *
 * @property string                                                                                                                      $preview_text
 * @property \System\Models\File                                                                                                         $preview_image
 *
 * @property string                                                                                                                      $description
 * @property \October\Rain\Database\Collection|\System\Models\File[]                                                                     $images
 *
 * @property string                                                                                                                      $price
 * @property string                                                                                                                      $old_price
 * @property float                                                                                                                       $price_value
 * @property float                                                                                                                       $old_price_value
 * @property array                                                                                                                       $price_list
 * @property string                                                                                                                      $currency
 * @property string                                                                                                                      $currency_code
 *
 * @property int                                                                                                                         $quantity
 *
 * Properties for Shopaholic
 * @see     \Lovata\PropertiesShopaholic\Classes\Event\OfferModelHandler::extendOfferItem
 * @property array                                                                                                                       $property_value_array
 * @property \Lovata\PropertiesShopaholic\Classes\Collection\PropertyCollection|\Lovata\PropertiesShopaholic\Classes\Item\PropertyItem[] $property
 *
 * Discounts for Shopaholic
 * @property string                                                                                                                      $discount_price
 * @property float                                                                                                                       $discount_price_value
 * @property int                                                                                                                         $discount_id
 * @property float                                                                                                                       $discount_value
 * @property string                                                                                                                      $discount_type
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

    protected $iActivePriceType = null;
    protected $sActiveCurrency = null;

    /**
     * Check element, active == true, trashed == false
     * @return bool
     */
    public function isActive()
    {
        return $this->active && !$this->trashed;
    }

    /**
     * Set active price type
     * @param int $iPriceTypeID
     * @return OfferItem
     */
    public function setActivePriceType($iPriceTypeID)
    {
        $this->iActivePriceType = $iPriceTypeID;

        return $this;
    }

    /**
     * Set active currency code
     * @param string $sActiveCurrencyCode
     * @return OfferItem
     */
    public function setActiveCurrency($sActiveCurrencyCode)
    {
        $this->sActiveCurrency = $sActiveCurrencyCode;

        return $this;
    }

    /**
     * Get active price type
     * @return int|null
     */
    public function getActivePriceType()
    {
        return $this->iActivePriceType;
    }

    /**
     * Get active currency code
     * @return int|null
     */
    public function getActiveCurrency()
    {
        if (!empty($this->sActiveCurrency)) {
            return $this->sActiveCurrency;
        }

        return CurrencyHelper::instance()->getActiveCurrencyCode();
    }

    /**
     * Get price_value attribute
     * @return float
     */
    protected function getPriceValueAttribute()
    {
        if (empty($this->iActivePriceType)) {
            $fPrice = $this->getAttribute('price_value');
        } else {
            $fPrice = array_get($this->price_list, $this->iActivePriceType.'.price');
        }

        $fPrice = CurrencyHelper::instance()->convert($fPrice, $this->getActiveCurrency());
        $this->setActiveCurrency(null);

        return $fPrice;
    }

    /**
     * Get old_price_value attribute
     * @return float
     */
    protected function getOldPriceValueAttribute()
    {
        if (empty($this->iActivePriceType)) {
            $fPrice = $this->getAttribute('old_price_value');
        } else {
            $fPrice = array_get($this->price_list, $this->iActivePriceType.'.old_price');
        }

        $fPrice = CurrencyHelper::instance()->convert($fPrice, $this->getActiveCurrency());
        $this->setActiveCurrency(null);

        return $fPrice;
    }

    /**
     * Set element object
     */
    protected function setElementObject()
    {
        $this->obElement = Offer::withTrashed()->with('main_price', 'price_link')->find($this->iElementID);
    }

    /**
     * Get currency value
     * @return null|string
     */
    protected function getCurrencyAttribute()
    {
        if (empty($this->sActiveCurrency)) {
            return CurrencyHelper::instance()->getActiveCurrencySymbol();
        }

        $sResult = CurrencyHelper::instance()->getCurrencySymbol($this->sActiveCurrency);
        $this->sActiveCurrency = null;

        return $sResult;
    }

    /**
     * Get currency code value
     * @return null|string
     */
    protected function getCurrencyCodeAttribute()
    {
        if (empty($this->sActiveCurrency)) {
            return CurrencyHelper::instance()->getActiveCurrencyCode();
        }

        $sResult = CurrencyHelper::instance()->getCurrencyCode($this->sActiveCurrency);
        $this->sActiveCurrency = null;

        return $sResult;
    }

    /**
     * Set element data from model object
     *
     * @return array
     */
    protected function getElementData()
    {
        $arResult = [
            'trashed' => $this->obElement->trashed(),
        ];

        return $arResult;
    }
}
