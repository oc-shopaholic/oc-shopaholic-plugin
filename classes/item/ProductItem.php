<?php namespace Lovata\Shopaholic\Classes\Item;

use Lovata\Toolbox\Classes\Item\ElementItem;

use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Collection\OfferCollection;

/**
 * Class ProductItem
 * @package Lovata\Shopaholic\Classes\Item
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @see     \Lovata\Shopaholic\Tests\Unit\Item\ProductItemTest
 * @link    https://github.com/lovata/oc-shopaholic-plugin/wiki/ProductItem
 *
 * @property int                                                     $id
 * @property bool                                                    $active
 * @property bool                                                    $trashed
 * @property string                                                  $name
 * @property string                                                  $slug
 * @property string                                                  $code
 *
 * @property int                                                     $category_id
 * @property CategoryItem                                            $category
 *
 * @property int                                                     $brand_id
 * @property BrandItem                                               $brand
 *
 * @property string                                                  $preview_text
 * @property \System\Models\File                                     $preview_image
 *
 * @property string                                                  $description
 * @property \October\Rain\Database\Collection|\System\Models\File[] $images
 *
 * @property array                                                   $offer_id_list
 * @property OfferCollection|OfferItem[]                             $offer
 *
 * Properties for Shopaholic
 * @see     \Lovata\PropertiesShopaholic\Classes\Event\ProductModelHandler::extendProductItem
 * @property \Lovata\PropertiesShopaholic\Classes\Collection\PropertyCollection|\Lovata\PropertiesShopaholic\Classes\Item\PropertyItem[] $property
 *
 * Reviews for Shopaholic
 * @see     \Lovata\ReviewsShopaholic\Classes\Event\ProductModelHandler::extendProductItem
 * @property float                                                                                                                       $rating
 * @property array                                                                                                                       $rating_data [1 => 0, 2 => 4, 3 => 7, 4 => 21, 5 => 48]
 * @property \Lovata\ReviewsShopaholic\Classes\Collection\ReviewCollection|\Lovata\ReviewsShopaholic\Classes\Item\ReviewItem[]           $review
 *
 * @method int getRatingCount(int $iRating)
 * @method int getRatingPercent(int $iRating)
 * @method int getRatingTotalCount()
 *
 * Related products for Shopaholic
 * @property \Lovata\Shopaholic\Classes\Collection\ProductCollection|ProductItem[]                                                       $related
 *
 * Accessories for Shopaholic
 * @property \Lovata\Shopaholic\Classes\Collection\ProductCollection|ProductItem[]                                                       $accessory
 *
 * Compare for Shopaholic
 * @method bool inCompare()
 */
class ProductItem extends ElementItem
{
    const MODEL_CLASS = Product::class;

    /** @var Product */
    protected $obElement = null;

    public $arRelationList = [
        'offer'    => [
            'class' => OfferCollection::class,
            'field' => 'offer_id_list',
        ],
        'category' => [
            'class' => CategoryItem::class,
            'field' => 'category_id',
        ],
        'brand'    => [
            'class' => BrandItem::class,
            'field' => 'brand_id',
        ],
    ];

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
        $this->obElement = Product::withTrashed()->find($this->iElementID);
    }

    /**
     * Set element data from model object
     *
     * @return array
     */
    protected function getElementData()
    {
        $arResult = [
            'offer_id_list' => $this->obElement->offer()->active()->lists('id'),
            'trashed'       => $this->obElement->trashed(),
        ];

        return $arResult;
    }
}
