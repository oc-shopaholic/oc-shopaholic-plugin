<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTableTaxCategoryRelation
 * @package Lovata\Shopaholic\Updates
 */
class CreateTableTaxCategoryRelation extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_tax_category_link';

    /**
     * Apply migration
     */
    public function up()
    {
        if (Schema::hasTable(self::TABLE_NAME)) {
            return;
        }

        Schema::create(self::TABLE_NAME, function (Blueprint $obTable) {
            $obTable->engine = 'InnoDB';
            $obTable->integer('tax_id')->unsigned();
            $obTable->integer('category_id')->unsigned();
            $obTable->primary(['category_id', 'tax_id'], 'tax_category');
        });
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
}
