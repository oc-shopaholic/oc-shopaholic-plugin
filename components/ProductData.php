<?php namespace Lovata\Shopaholic\Components;

use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Models\Product;
use Request;

/**
 * Class ProductData
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductData extends ComponentBase
{
    protected $iProductId;

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
        $this->iProductId = Request::get('product_id');
        return Product::getCacheData($this->iProductId);
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
        
        return $arResult;
    }
}
