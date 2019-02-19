<?php namespace Lovata\Shopaholic\Components;

use Input;
use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Classes\Collection\CurrencyCollection;
use Lovata\Shopaholic\Classes\Helper\CurrencyHelper;

/**
 * Class CurrencyList
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CurrencyList extends ComponentBase
{
    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'          => 'lovata.shopaholic::lang.component.currency_list_name',
            'description'   => 'lovata.shopaholic::lang.component.currency_list_description',
        ];
    }

    /**
     * Make element collection
     * @param array $arElementIDList
     *
     * @return CurrencyCollection
     */
    public function make($arElementIDList = null)
    {
        return CurrencyCollection::make($arElementIDList);
    }

    /**
     * Axax request, switch active currency
     */
    public function onSwitch()
    {
        $sActiveCurrency = Input::get('currency');

        $this->switch($sActiveCurrency);
    }

    /**
     * switch active currency
     * @param string $sCurrencyCode
     */
    public function switch($sCurrencyCode = null)
    {
        CurrencyHelper::instance()->switchActive($sCurrencyCode);
    }

    /**
     * Method for ajax request with empty response
     * @return bool
     */
    public function onAjaxRequest()
    {
        return true;
    }
}
