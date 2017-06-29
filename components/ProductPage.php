<?php namespace Lovata\Shopaholic\Components;

use Event;
use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Toolbox\Traits\Helpers\TraitComponentNotFoundResponse;

/**
 * Class ProductPage
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductPage extends ComponentBase
{
    use TraitComponentNotFoundResponse;

    /** @var null|Product */
    protected $obProduct = null;

    /** @var  ProductItem */
    protected $obProductItem;

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
     * @return array
     */
    public function defineProperties()
    {
        $arProperties = $this->getElementPageProperties();
        return $arProperties;
    }

    /**
     * @return \Illuminate\Http\Response|null
     */
    public function onRun()
    {
        //Get product slug
        $sProductSlug = $this->property('slug');
        if (empty($sProductSlug)) {
            return $this->getErrorResponse();
        }

        //Get product by slug
        /** @var Product $obProduct */
        $obProduct = Product::active()->getBySlug($sProductSlug)->first();
        if (empty($obProduct)) {
            return $this->getErrorResponse();
        }

        $this->obProduct = $obProduct;

        //Get product item
        $this->obProductItem = ProductItem::make($obProduct->id, $obProduct);

        //Send event
        Event::fire('shopaholic.product.open', [$obProduct]);

        return null;
    }

    /**
     * Get product item
     * @return ProductItem
     */
    public function get()
    {
        return $this->obProductItem;
    }
}