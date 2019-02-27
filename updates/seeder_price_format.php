<?php namespace Lovata\Shopaholic\Updates;

use Seeder;
use Lovata\Toolbox\Models\Settings;

/**
 * Class SeederPriceFormat
 * @package Lovata\Shopaholic\Updates
 */
class SeederPriceFormat extends Seeder
{
    /**
     * Run seeder
     */
    public function run()
    {
        Settings::set('decimals', \Lovata\Shopaholic\Models\Settings::getValue('decimals'));
        Settings::set('dec_point', \Lovata\Shopaholic\Models\Settings::getValue('dec_point'));
        Settings::set('thousands_sep', \Lovata\Shopaholic\Models\Settings::getValue('thousands_sep'));
    }
}
