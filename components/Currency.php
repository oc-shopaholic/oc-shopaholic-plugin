<?php namespace Lovata\Shopaholic\Components;

use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Models\Settings;

/**
 * Class Currency
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Currency extends ComponentBase
{
    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'          => 'lovata.shopaholic::lang.component.currency_name',
            'description'   => 'lovata.shopaholic::lang.component.currency_description',
        ];
    }

    /**
     * @return null|string
     */
    public function get() {
        return Settings::getValue('currency');
    }
}