<?php namespace Lovata\Shopaholic\Components;

use Event;
use Lovata\Toolbox\Classes\Component\ElementPage;

use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Item\ProductItem;

/**
 * Class ProductPage
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @link https://github.com/lovata/oc-shopaholic-plugin/wiki/ProductPage
 *
 * Compare for Shopaholic
 * @method array onAddToCompare()
 * @method array onRemoveFromCompare()
 * @method void onClearCompareList()
 *
 * Viewed products for Shopaholic
 * @method array onRemoveFromViewedProductList()
 * @method void onClearViewedProductList()
 *
 * Wish list for Shopaholic
 * @method array onAddToWishList()
 * @method array onRemoveFromWishList()
 * @method void onClearWishList()
 */
class ProductPage extends ElementPage
{
    protected $bNeedSmartURLCheck = true;

    /** @var \Lovata\Shopaholic\Models\Product */
    protected $obElement;

    /** @var \Lovata\Shopaholic\Classes\Item\ProductItem */
    protected $obElementItem;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'lovata.shopaholic::lang.component.product_page_name',
            'description' => 'lovata.shopaholic::lang.component.product_page_description',
        ];
    }

    /**
     * Get element object
     * @param string $sElementSlug
     * @return Product
     */
    protected function getElementObject($sElementSlug)
    {
        if (empty($sElementSlug)) {
            return null;
        }


        if ($this->isSlugTranslatable()) {
            $obElement = Product::active()->transWhere('slug', $sElementSlug)->first();
            if (!$this->checkTransSlug($obElement, $sElementSlug)) {
                $obElement = null;
            }
        } else {
            $obElement = Product::active()->getBySlug($sElementSlug)->first();
        }
        if (!empty($obElement)) {
            Event::fire('shopaholic.product.open', [$obElement]);
        }

        return $obElement;
    }

    /**
     * Make new element item
     * @param int     $iElementID
     * @param Product $obElement
     * @return ProductItem
     */
    protected function makeItem($iElementID, $obElement)
    {
        return ProductItem::make($iElementID, $obElement);
    }
}
