<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTableTaxProductRelation
 * @package Lovata\Shopaholic\Updates
 */
class CreateTableTaxProductRelation extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_tax_product_link';

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
            $obTable->integer('product_id')->unsigned();
            $obTable->primary(['product_id', 'tax_id'], 'tax_product');
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
