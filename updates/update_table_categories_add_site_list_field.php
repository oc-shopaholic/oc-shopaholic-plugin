<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * @package Lovata\Shopaholic\Updates
 */
class UpdateTableCategoriesAddSiteListField extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_categories';
    const COLUMN_LIST = [
        'site_list',
    ];

    /**
     * Apply migration
     */
    public function up()
    {
        if (!Schema::hasTable(self::TABLE_NAME) || Schema::hasColumns(self::TABLE_NAME, self::COLUMN_LIST)) {
            return;
        }

        Schema::table(self::TABLE_NAME, function (Blueprint $obTable) {
            $obTable->json('site_list')->nullable();
        });
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        if (!Schema::hasTable(self::TABLE_NAME) || !Schema::hasColumns(self::TABLE_NAME, self::COLUMN_LIST)) {
            return;
        }

        Schema::table(self::TABLE_NAME, function (Blueprint $obTable) {
            $obTable->dropColumn(self::COLUMN_LIST);
        });
    }
}
