<?php namespace Lovata\Shopaholic\Components;

use Lovata\Toolbox\Classes\Component\ElementData;

use Lovata\Shopaholic\Classes\Item\PromoBlockItem;

/**
 * Class PromoBlockData
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class PromoBlockData extends ElementData
{
    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'          => 'lovata.shopaholic::lang.component.promo_block_data_name',
            'description'   => 'lovata.shopaholic::lang.component.promo_block_data_description',
        ];
    }

    /**
     * Make new element item
     * @param int $iElementID
     * @return PromoBlockItem
     */
    protected function makeItem($iElementID)
    {
        return PromoBlockItem::make($iElementID);
    }
}
