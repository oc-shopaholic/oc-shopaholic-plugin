<?php namespace Lovata\Shopaholic\Components;

use Input;
use Request;
use Lovata\Shopaholic\Classes\Item\BrandItem;
use Cms\Classes\ComponentBase;

/**
 * Class BrandData
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class BrandData extends ComponentBase
{
    protected $iBrandID = null;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'          => 'lovata.shopaholic::lang.component.brand_data_name',
            'description'   => 'lovata.shopaholic::lang.component.brand_data_description',
        ];
    }

    /**
     * Ajax listener
     * @return array|null
     */
    public function onGetData()
    {
        $this->iBrandID = Input::get('brand_id');
        $obBrandItem = BrandItem::make($this->iBrandID);

        return $obBrandItem->getArray();
    }

    /**
     * Ajax listener
     */
    public function onAjaxRequest()
    {
        $this->iBrandID = Input::get('brand_id');
    }

    /**
     * Get brand data
     * @param int $iBrandID
     * @return BrandItem
     */
    public function get($iBrandID = null)
    {
        if(Request::ajax() && empty($iBrandID)) {
            $iBrandID = $this->iBrandID;
        }

        $obBrandItem = BrandItem::make($iBrandID);
        return $obBrandItem;
    }
}
