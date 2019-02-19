<?php namespace Lovata\Shopaholic\Updates;

use Seeder;
use Lovata\Shopaholic\Models\Currency;
use Lovata\Shopaholic\Models\Settings;

/**
 * Class SeederCreateDefaultCurrency
 * @package Lovata\Shopaholic\Updates
 */
class SeederCreateDefaultCurrency extends Seeder
{
    /**
     * Run seeder
     */
    public function run()
    {
        $iCurrencyCount = Currency::count();
        if ($iCurrencyCount > 0) {
            return;
        }

        $arDefaultCurrencyData = [
            'active'     => true,
            'is_default' => true,
            'name'       => 'USD',
            'code'       => 'USD',
            'symbol'     => '$',
            'rate'       => 1,
            'sort_order' => 1,
        ];

        //Get currency value from settings
        $sCurrency = trim(Settings::getValue('currency'));
        if (!empty($sCurrency)) {
            $arDefaultCurrencyData['name'] = $sCurrency;
            $arDefaultCurrencyData['code'] = $sCurrency;
            $arDefaultCurrencyData['symbol'] = $sCurrency;
        }

        try {
            Currency::create($arDefaultCurrencyData);
        } catch (\Exception $obException) {
            return;
        }
    }
}
