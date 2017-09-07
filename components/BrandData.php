<?php namespace Lovata\Shopaholic\Components;

use Lovata\Toolbox\Classes\Component\ElementData;

use Lovata\Shopaholic\Classes\Item\BrandItem;

/**
 * Class BrandData
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @link https://github.com/lovata/oc-shopaholic-plugin/wiki/BrandData
 */
class BrandData extends ElementData
{
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
     * Make new element item
     * @param int $iElementID
     * @return BrandItem
     */
    protected function makeItem($iElementID)
    {
        return BrandItem::make($iElementID);
    }
}
