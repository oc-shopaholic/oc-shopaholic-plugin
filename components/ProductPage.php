<?php namespace Lovata\Shopaholic\Components;

use Event;
use Lovata\Toolbox\Classes\ComponentTraitNotFoundResponse;
use Lovata\Shopaholic\Models\Product;
use Cms\Classes\ComponentBase;

/**
 * Class ProductPage
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductPage extends ComponentBase
{
    use ComponentTraitNotFoundResponse;

    /** @var null|Product */
    protected $obProduct = null;

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
        $bDisplayError404 = $this->property('error_404') == 'on' ? true : false;

        //Get product slug
        $sProductSlug = $this->property('slug');
        if (empty($sProductSlug)) {
            return $this->getErrorResponse($bDisplayError404);
        }

        //Get product by slug
        /** @var Product $obProduct */
        $obProduct = Product::active()->getBySlug($sProductSlug)->first();
        if (empty($obProduct)) {
            return $this->getErrorResponse($bDisplayError404);
        }

        $this->obProduct = $obProduct;

        //Send event
        Event::fire('shopaholic.product.open', [$obProduct]);

        return null;
    }

    /**
     * Get product data
     * @return array
     */
    public function get()
    {
        if(empty($this->obProduct)) {
            return null;
        }
        return Product::getCacheData($this->obProduct->id, $this->obProduct);
    }
}