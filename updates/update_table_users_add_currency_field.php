<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class UpdateTableUsersAddCurrencyField
 * @package Lovata\Shopaholic\Updates
 */
class UpdateTableUsersAddCurrencyField extends Migration
{
    /**
     * Apply migration
     */
    public function up()
    {
        if (Schema::hasTable('lovata_buddies_users') && !Schema::hasColumn('lovata_buddies_users', 'active_currency_code')) {

            Schema::table('lovata_buddies_users', function (Blueprint $obTable) {
                $obTable->string('active_currency_code')->nullable();
            });
        }

        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'active_currency_code')) {

            Schema::table('users', function (Blueprint $obTable) {
                $obTable->string('active_currency_code')->nullable();
            });
        }
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        if (Schema::hasTable('lovata_buddies_users') && Schema::hasColumn('lovata_buddies_users', 'active_currency_code')) {
            Schema::table('lovata_buddies_users', function (Blueprint $obTable) {
                $obTable->dropColumn(['active_currency_code']);
            });
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'active_currency_code')) {
            Schema::table('users', function (Blueprint $obTable) {
                $obTable->dropColumn(['active_currency_code']);
            });
        }
    }
}
