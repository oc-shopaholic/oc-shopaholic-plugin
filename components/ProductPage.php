<?php namespace Lovata\Shopaholic\Components;

use Event;
use Lang;
use Response;
use Lovata\Shopaholic\Models\Product;
use Cms\Classes\ComponentBase;
use System\Classes\PluginManager;

/**
 * Class ProductPage
 * @package Lovata\Shopaholic\Components
 * @author Denis Plisko, d.plisko@lovata.com, LOVATA Group
 */
class ProductPage extends ComponentBase
{

    /**
     * @var null|Product
     */
    protected $obProduct = null;

    public function componentDetails()
    {
        return [
            'name' => Lang::get('lovata.shopaholic::lang.component.product_data_name'),
            'description' => Lang::get('lovata.shopaholic::lang.component.product_data_description'),
        ];
    }

    public function defineProperties()
    {
        return [
            'error_404' => [
                'title' => Lang::get('lovata.shopaholic::lang.component.category_data_property_name_error_404'),
                'description' => Lang::get('lovata.shopaholic::lang.component.category_data_property_description_error_404'),
                'default' => 'on',
                'type' => 'dropdown',
                'options' => [
                    'on' => Lang::get('lovata.shopaholic::lang.component.property_value_on'),
                    'off' => Lang::get('lovata.shopaholic::lang.component.property_value_off'),
                ],
            ],
            'slug' => [
                'title'             => Lang::get('lovata.shopaholic::lang.component.property_slug'),
                'type'              => 'string',
                'default'           => '{{ :slug }}',
            ],
        ];
    }

    public function onRun()
    {

        $bDisplayError404 = $this->property('error_404') == 'on' ? true : false;

        //Get product slug
        $sProductSlug = $this->property('slug');

        if (empty($sProductSlug)) {

            if (!$bDisplayError404) {
                return;
            }

            return Response::make($this->controller->run('404')->getContent(), 404);
        }

        //Get product by slug
        /** @var Product $obProduct */
        $obProduct = Product::active()->slug($sProductSlug)->first();
        if (empty($obProduct)) {

            if (!$bDisplayError404) {
                return;
            }

            return Response::make($this->controller->run('404')->getContent(), 404);
        }

        $this->obProduct = $obProduct;

        //Send event
        Event::fire('shopaholic.product.open', [$obProduct]);
        
        return;
    }

    /**
     * Get Product data
     * @return array
     */
    public function get()
    {
        $arResult = [];
        if(!empty($this->obProduct)) {
            $arResult = Product::getCacheData($this->obProduct->id, $this->obProduct);
        }

        if(empty($arResult)) {
            return $arResult;
        }

        //Add related products data
        if(PluginManager::instance()->hasPlugin('Lovata.RelatedProductsShopaholic')) {

            $arResult['related_products'] = [];
            if(!empty($arResult['related_product_id'])) {
                foreach($arResult['related_product_id'] as $iRelatedProductID) {

                    $arRelatedProductData = Product::getCacheData($iRelatedProductID);
                    if(!empty($arRelatedProductData)) {
                        $arResult['related_products'][$iRelatedProductID] = $arRelatedProductData;
                    }
                }
            }
        }

        return $arResult;
    }
}