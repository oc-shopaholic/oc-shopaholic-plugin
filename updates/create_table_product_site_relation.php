<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * @package Lovata\Shopaholic\Updates
 */
class CreateTableProductBlockRelation extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_product_site_relation';

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
            $obTable->integer('product_id')->unsigned();
            $obTable->integer('site_id')->unsigned();
            $obTable->primary(['product_id', 'site_id'], 'product_site');
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
