<?php namespace Lovata\Shopaholic\Components;

use Input;
use Request;
use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Models\Product;

/**
 * Class ProductData
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductData extends ComponentBase
{
    protected $iProductID = null;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'          => 'lovata.shopaholic::lang.component.product_data_name',
            'description'   => 'lovata.shopaholic::lang.component.product_data_description',
        ];
    }

    /**
     * Ajax listener
     * @return array|null
     */
    public function onGetProductData()
    {
        $this->iProductID = Input::get('product_id');
        return Product::getCacheData($this->iProductID);
    }

    /**
     * Ajax listener
     */
    public function onAjaxRequest()
    {
        $this->iProductID = Input::get('product_id');
    }

    /**
     * Get product data
     * @param int $iProductID
     * @return array
     */
    public function get($iProductID = null)
    {
        if(Request::ajax() && empty($iProductID)) {
            $arResult = Product::getCacheData($this->iProductID);
        } else {
            $arResult = Product::getCacheData($iProductID);
        }
        
        return $arResult;
    }
}
