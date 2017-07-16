<?php namespace Lovata\Shopaholic\Components;

use Event;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Toolbox\Components\ElementPage;

/**
 * Class ProductPage
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductPage extends ElementPage
{
    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'          => 'lovata.shopaholic::lang.component.product_page_name',
            'description'   => 'lovata.shopaholic::lang.component.product_page_description',
        ];
    }

    /**
     * Get element object
     * @param string $sElementSlug
     * @return Product
     */
    protected function getElementObject($sElementSlug)
    {
        if(empty($sElementSlug)) {
            return null;
        }

        return Product::active()->getBySlug($sElementSlug)->first();
    }

    /**
     * Make new element item
     * @param int $iElementID
     * @param Product $obElement
     * @return ProductItem
     */
    protected function makeItem($iElementID, $obElement)
    {
        return ProductItem::make($iElementID, $obElement);
    }

    /**
     * @return \Illuminate\Http\Response|null
     */
    public function onRun()
    {
        $obResult = parent::onRun();
        if($obResult === null) {

            //Send event
            Event::fire('shopaholic.product.open', [$this->obElement]);
        }

        return $obResult;
    }
}