<?php namespace Lovata\Shopaholic\Components;

use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Models\Product;
use Request;
use Lang;
use System\Classes\PluginManager;

/**
 * Class ProductData
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * @author Denis Plisko, d.plisko@lovata.com, LOVATA Group
 */
class ProductData extends ComponentBase
{
    protected $iProductId;
    
    public function componentDetails()
    {
        return [
            'name' => Lang::get('lovata.shopaholic::lang.component.get_product_name'),
            'description' => Lang::get('lovata.shopaholic::lang.component.get_product_description'),
        ];
    }

    /**
     * Ajax listener
     */
    public function onGetProduct()
    {
        $this->iProductId = Request::get('product_id');
    }

    /**
     * Get product data
     * @param int $iProductID
     * @return array
     */
    public function get($iProductID)
    {
        if(Request::ajax() && empty($iProductID)) {
            $arResult = Product::getCacheData($this->iProductId);
        } else {
            $arResult = Product::getCacheData($iProductID);
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
