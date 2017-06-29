<?php namespace Lovata\Shopaholic\Components;

use Input;
use Request;
use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Classes\Item\ProductItem;

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
    public function onGetData()
    {
        $this->iProductID = Input::get('product_id');
        $obProductItem = ProductItem::make($this->iProductID);

        return $obProductItem->getArray();
    }

    /**
     * Ajax listener
     */
    public function onAjaxRequest()
    {
        $this->iProductID = Input::get('product_id');
    }

    /**
     * Get product item
     * @param int $iProductID
     * @return ProductItem
     */
    public function get($iProductID = null)
    {
        if(Request::ajax() && empty($iProductID)) {
            $iProductID = $this->iProductID;
        }

        $obProductItem = ProductItem::make($iProductID);
        return $obProductItem;
    }
}
