<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTablePromoBlockRelation
 * @package Lovata\Shopaholic\Updates
 */
class CreateTablePromoBlockRelation extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_promo_block_relation';

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
            $obTable->integer('promo_id')->unsigned();
            $obTable->integer('product_id')->unsigned();
            $obTable->primary(['promo_id', 'product_id'], 'product_promo');
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
