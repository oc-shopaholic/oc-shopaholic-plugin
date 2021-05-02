<?php namespace Lovata\Shopaholic\Classes\Item;

use Lovata\Toolbox\Classes\Helper\PriceHelper;
use Lovata\Toolbox\Classes\Item\ElementItem;
use Lovata\Toolbox\Traits\Helpers\PriceHelperTrait;

use Lovata\Shopaholic\Classes\Helper\MeasureHelper;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Settings;
use Lovata\Shopaholic\Classes\Helper\TaxHelper;
use Lovata\Shopaholic\Classes\Helper\CurrencyHelper;
use Lovata\Shopaholic\Classes\Helper\PriceTypeHelper;

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
 * @property double                                                                                                                      $weight
 * @property double                                                                                                                      $height
 * @property double                                                                                                                      $length
 * @property double                                                                                                                      $width
 * @property double                                                                                                                      $quantity_in_unit
 * @property int                                                                                                                         $measure_id
 * @property MeasureItem                                                                                                                 $measure
 * @property int                                                                                                                         $measure_of_unit_id
 * @property MeasureItem                                                                                                                 $measure_of_unit
 * @property MeasureItem                                                                                                                 $dimensions_measure
 * @property MeasureItem                                                                                                                 $weight_measure
 *
 * @property string                                                                                                                      $preview_text
 * @property \System\Models\File                                                                                                         $preview_image
 *
 * @property string                                                                                                                      $description
 * @property \October\Rain\Database\Collection|\System\Models\File[]                                                                     $images
 *
 * @property string                                                                                                                      $price
 * @property float                                                                                                                       $price_value
 * @property string                                                                                                                      $tax_price
 * @property float                                                                                                                       $tax_price_value
 * @property string                                                                                                                      $price_without_tax
 * @property float                                                                                                                       $price_without_tax_value
 * @property string                                                                                                                      $price_with_tax
 * @property float                                                                                                                       $price_with_tax_value
 *
 * @property string                                                                                                                      $old_price
 * @property float                                                                                                                       $old_price_value
 * @property string                                                                                                                      $tax_old_price
 * @property float                                                                                                                       $tax_old_price_value
 * @property string                                                                                                                      $old_price_without_tax
 * @property float                                                                                                                       $old_price_without_tax_value
 * @property string                                                                                                                      $old_price_with_tax
 * @property float                                                                                                                       $old_price_with_tax_value
 *
 * @property string                                                                                                                      $discount_price
 * @property float                                                                                                                       $discount_price_value
 * @property string                                                                                                                      $tax_discount_price
 * @property float                                                                                                                       $tax_discount_price_value
 * @property string                                                                                                                      $discount_price_without_tax
 * @property float                                                                                                                       $discount_price_without_tax_value
 * @property string                                                                                                                      $discount_price_with_tax
 * @property float                                                                                                                       $discount_price_with_tax_value
 *
 * @property array                                                                                                                       $price_list
 * @property string                                                                                                                      $currency
 * @property string                                                                                                                      $currency_code
 *
 * @property float                                                                                                                       $tax_percent
 * @property \Lovata\Shopaholic\Classes\Collection\TaxCollection|\Lovata\Shopaholic\Classes\Item\TaxItem[]                               $tax_list
 *
 * @property int                                                                                                                         $quantity
 *
 * Properties for Shopaholic
 * @see     \Lovata\PropertiesShopaholic\Classes\Event\OfferModelHandler::extendOfferItem
 * @property array                                                                                                                       $property_value_array
 * @property \Lovata\PropertiesShopaholic\Classes\Collection\PropertyCollection|\Lovata\PropertiesShopaholic\Classes\Item\PropertyItem[] $property
 *
 * Discounts for Shopaholic
 * @property int                                                                                                                         $discount_id
 * @property float                                                                                                                       $discount_value
 * @property string                                                                                                                      $discount_type
 *
 * Subscriptions for Shopaholic
 * @property int                                                                                                                         $subscription_period_id
 * @property \Lovata\SubscriptionsShopaholic\Classes\Item\SubscriptionPeriodItem                                                         $subscription_period
 *
 * YandexMarket for Shopaholic
 * @property \System\Models\File                                                                                                         $preview_image_yandex
 * @property \October\Rain\Database\Collection|\System\Models\File[]                                                                     $images_yandex
 *
 * Facebook for Shopaholic
 * @property \System\Models\File                                                                                                         $preview_image_facebook
 * @property \October\Rain\Database\Collection|\System\Models\File[]                                                                     $images_facebook
 *
 * VKontakte for Shopaholic
 * @property \System\Models\File                                                                                                         $preview_image_vkontakte
 * @property \October\Rain\Database\Collection|\System\Models\File[]                                                                     $images_vkontakte
 *
 * Downloadable file for Shopaholic
 * @property \October\Rain\Database\Collection|\System\Models\File[]                                                                     $downloadable_file
 */
class OfferItem extends ElementItem
{
    use PriceHelperTrait;

    const MODEL_CLASS = Offer::class;

    public static $arQueryWith = ['preview_image', 'images', 'main_price', 'price_link'];

    /** @var Offer */
    protected $obElement = null;

    public $arRelationList = [
        'product'         => [
            'class' => ProductItem::class,
            'field' => 'product_id',
        ],
        'measure' => [
            'class' => MeasureItem::class,
            'field' => 'measure_id',
        ],
    ];

    public $arPriceField = [
        'price',
        'tax_price',
        'price_without_tax',
        'price_with_tax',
        'old_price',
        'tax_old_price',
        'old_price_without_tax',
        'old_price_with_tax',
        'discount_price',
        'tax_discount_price',
        'discount_price_without_tax',
        'discount_price_with_tax',
    ];

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
        if (empty($this->iActivePriceType)) {
            $this->iActivePriceType = PriceTypeHelper::instance()->getActivePriceTypeID();
        }

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
        $iActivePriceType = $this->getActivePriceType();
        if (empty($iActivePriceType)) {
            $fPrice = $this->getAttribute('price_value');
        } else {
            $fPrice = array_get($this->price_list, $iActivePriceType.'.price');
        }

        $fPrice = CurrencyHelper::instance()->convert($fPrice, $this->getActiveCurrency());

        return $fPrice;
    }

    /**
     * Get old_price_value attribute
     * @return float
     */
    protected function getOldPriceValueAttribute()
    {
        $iActivePriceType = $this->getActivePriceType();
        if (empty($iActivePriceType)) {
            $fPrice = $this->getAttribute('old_price_value');
        } else {
            $fPrice = array_get($this->price_list, $iActivePriceType.'.old_price');
        }

        $fPrice = CurrencyHelper::instance()->convert($fPrice, $this->getActiveCurrency());

        return $fPrice;
    }

    /**
     * Get discount_price_value attribute
     * @return float
     */
    protected function getDiscountPriceValueAttribute()
    {
        $fOldPrice = $this->old_price_value;
        $fPrice = $this->price_value;

        $fDiscountPrice = 0;
        if ($fOldPrice > 0) {
            $fDiscountPrice = PriceHelper::round($fOldPrice - $fPrice);
        }

        $fPrice = CurrencyHelper::instance()->convert($fDiscountPrice, $this->getActiveCurrency());

        return $fPrice;
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
     * Get tax_price_value attribute value
     * @return float
     */
    protected function getTaxPriceValueAttribute()
    {
        $fPrice = PriceHelper::round($this->price_with_tax_value - $this->price_without_tax_value);

        return $fPrice;
    }

    /**
     * Get tax_old_price_value attribute value
     * @return float
     */
    protected function getTaxOldPriceValueAttribute()
    {
        $fPrice = PriceHelper::round($this->old_price_with_tax_value - $this->old_price_without_tax_value);

        return $fPrice;
    }

    /**
     * Get tax_discount_price_value attribute value
     * @return float
     */
    protected function getTaxDiscountPriceValueAttribute()
    {
        $fPrice = PriceHelper::round($this->discount_price_with_tax_value - $this->discount_price_without_tax_value);

        return $fPrice;
    }

    /**
     * Get price_with_tax_value attribute value
     * @return float
     */
    protected function getPriceWithTaxValueAttribute()
    {
        $fPrice = TaxHelper::instance()->getPriceWithTax($this->price_value, $this->tax_percent);

        return $fPrice;
    }

    /**
     * Get old_price_with_tax_value attribute value
     * @return float
     */
    protected function getOldPriceWithTaxValueAttribute()
    {
        $fPrice = TaxHelper::instance()->getPriceWithTax($this->old_price_value, $this->tax_percent);

        return $fPrice;
    }

    /**
     * Get discount_price_with_tax_value attribute value
     * @return float
     */
    protected function getDiscountPriceWithTaxValueAttribute()
    {
        $fPrice = TaxHelper::instance()->getPriceWithTax($this->discount_price_value, $this->tax_percent);

        return $fPrice;
    }

    /**
     * Get price_without_tax_value attribute value
     * @return float
     */
    protected function getPriceWithoutTaxValueAttribute()
    {
        $fPrice = TaxHelper::instance()->getPriceWithoutTax($this->price_value, $this->tax_percent);

        return $fPrice;
    }

    /**
     * Get old_price_without_tax_value attribute value
     * @return float
     */
    protected function getOldPriceWithoutTaxValueAttribute()
    {
        $fPrice = TaxHelper::instance()->getPriceWithoutTax($this->old_price_value, $this->tax_percent);

        return $fPrice;
    }

    /**
     * Get discount_price_without_tax_value attribute value
     * @return float
     */
    protected function getDiscountPriceWithoutTaxValueAttribute()
    {
        $fPrice = TaxHelper::instance()->getPriceWithoutTax($this->discount_price_value, $this->tax_percent);

        return $fPrice;
    }

    /**
     * Get tax_percent attribute value
     * @return float
     */
    protected function getTaxPercentAttribute()
    {
        $fTaxPercent = $this->getAttribute('tax_percent');
        if ($fTaxPercent === null) {
            $fTaxPercent = TaxHelper::instance()->getTaxPercent($this->tax_list);
            $this->setAttribute('tax_percent', $fTaxPercent);
        }

        return $fTaxPercent;
    }

    /**
     * Get tax_list attribute value
     * @return \Lovata\Shopaholic\Classes\Collection\TaxCollection|TaxItem[]
     */
    protected function getTaxListAttribute()
    {
        $obTaxList = $this->getAttribute('tax_list');
        if ($obTaxList === null) {
            $obTaxList = $this->getAppliedTaxList();
            $this->setAttribute('tax_list', $obTaxList);
        }

        return $obTaxList;
    }

    /**
     * Get measure of one unit
     * @return \Lovata\Shopaholic\Classes\Item\MeasureItem
     */
    protected function getMeasureOfUnitAttribute()
    {
        $iMeasureID = $this->measure_of_unit_id;
        if (empty($iMeasureID)) {
            $iMeasureID = Settings::getValue('measure_of_unit');
        }

        $obMeasureItem = MeasureItem::make($iMeasureID);

        return $obMeasureItem;
    }

    /**
     * Get dimensions unit measure
     * @return \Lovata\Shopaholic\Classes\Item\MeasureItem
     */
    protected function getDimensionsMeasureAttribute()
    {
        $obMeasureItem = MeasureHelper::instance()->getDimensionsMeasureItem();

        return $obMeasureItem;
    }

    /**
     * Get weight unit measure
     * @return \Lovata\Shopaholic\Classes\Item\MeasureItem
     */
    protected function getWeightMeasureAttribute()
    {
        $obMeasureItem = MeasureHelper::instance()->getWeightMeasureItem();

        return $obMeasureItem;
    }

    /**
     * Get applied tax list
     * @return \Lovata\Shopaholic\Classes\Collection\TaxCollection|\Lovata\Shopaholic\Classes\Item\TaxItem[]
     */
    protected function getAppliedTaxList()
    {
        $obResultTaxList = TaxHelper::instance()->getTaxList();
        if ($obResultTaxList->isEmpty()) {
            return $obResultTaxList;
        }

        foreach ($obResultTaxList as $obTaxItem) {
            $bSkipTax = !$obTaxItem->is_global
                && !$obTaxItem->isAvailableForCategory($this->product->category)
                && !$obTaxItem->isAvailableForProduct($this->product)
                && !$obTaxItem->isAvailableForCountry(TaxHelper::instance()->getActiveCountry())
                && !$obTaxItem->isAvailableForState(TaxHelper::instance()->getActiveState());

            if ($bSkipTax) {
                $obResultTaxList->exclude($obTaxItem->id);
            }
        }

        return $obResultTaxList;
    }

    /**
     * Set element data from model object
     *
     * @return array
     */
    protected function getElementData()
    {
        $obDefaultCurrency = CurrencyHelper::instance()->getDefault();
        $sCurrencyCode = !empty($obDefaultCurrency) ? $obDefaultCurrency->code : null;

        $arResult = [
            'price_value'     => $this->obElement->setActiveCurrency($sCurrencyCode)->setActivePriceType(null)->price_value,
            'old_price_value' => $this->obElement->setActiveCurrency($sCurrencyCode)->setActivePriceType(null)->old_price_value,
            'trashed'         => $this->obElement->trashed(),
        ];

        return $arResult;
    }
}
