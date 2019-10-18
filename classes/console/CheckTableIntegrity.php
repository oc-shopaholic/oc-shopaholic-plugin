<?php namespace Lovata\Shopaholic\Classes\Console;

use Illuminate\Console\Command;

/**
 * Class CheckTableIntegrity
 * @package Lovata\Shopaholic\Classes\Console
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CheckTableIntegrity extends Command
{
    /**
     * @var string command name.
     */
    protected $name = 'shopaholic:check.table.integrity';

    /**
     * @var string The console command description.
     */
    protected $description = 'Check table integrity of plugins';

    protected $arMigrationList = [
        ['path' => 'plugins/lovata/shopaholic/updates/update_table_users_add_currency_field.php', 'class' => '\Lovata\Shopaholic\Updates\UpdateTableUsersAddCurrencyField'],
        ['path' => 'plugins/lovata/compareshopaholic/updates/update_table_users.php', 'class' => '\Lovata\CompareShopaholic\Updates\UpdateTableUsers'],
        ['path' => 'plugins/lovata/ordersshopaholic/updates/table_update_taxes_add_applied_to_shipping_price.php', 'class' => '\Lovata\OrdersShopaholic\Updates\TableUpdateTaxesAddAppliedToShippingPrice'],
        ['path' => 'plugins/lovata/searchshopaholic/updates/update_table_tag.php', 'class' => '\Lovata\SearchShopaholic\Updates\UpdateTableTag'],
        ['path' => 'plugins/lovata/sphinxshopaholic/updates/update_table_tag.php', 'class' => '\Lovata\SphinxShopaholic\Updates\UpdateTableTag'],
        ['path' => 'plugins/lovata/viewedproductsshopaholic/updates/update_table_users.php', 'class' => '\Lovata\ViewedProductsShopaholic\Updates\UpdateTableUsers'],
        ['path' => 'plugins/lovata/wishlistshopaholic/updates/update_table_users.php', 'class' => '\Lovata\WishListShopaholic\Updates\UpdateTableUsers'],
    ];

    /**
     * Execute the console command.
     * @throws \Throwable
     */
    public function handle()
    {
        foreach ($this->arMigrationList as $arMigrationData) {
            $sClassName = $arMigrationData['class'];
            $sFilePath = base_path($arMigrationData['path']);
            if (!file_exists($sFilePath)) {
                continue;
            }

            include_once $sFilePath;

            if (!class_exists($sClassName)) {
                continue;
            }

            /** @var \October\Rain\Database\Updates\Migration $obMigration */
            $obMigration = new $sClassName();
            $obMigration->up();
        }
    }
}
