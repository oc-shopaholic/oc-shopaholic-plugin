<?php namespace Lovata\Shopaholic\Components;

use Event;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Toolbox\Classes\Component\ElementPage;

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
 */
class ProductPage extends ElementPage
{
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

        $obElement = Product::active()->getBySlug($sElementSlug)->first();
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
