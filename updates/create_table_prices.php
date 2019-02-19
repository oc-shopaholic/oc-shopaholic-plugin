<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTablePrices
 * @package Lovata\Shopaholic\Updates
 */
class CreateTablePrices extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_prices';

    /**
     * Apply migration
     */
    public function up()
    {
        if (Schema::hasTable(self::TABLE_NAME)) {
            return;
        }

        Schema::create(self::TABLE_NAME, function (Blueprint $obTable)
        {
            $obTable->engine = 'InnoDB';
            $obTable->increments('id')->unsigned();
            $obTable->integer('item_id')->unsigned();
            $obTable->string('item_type');
            $obTable->decimal('price', 15, 2)->nullable();
            $obTable->decimal('old_price', 15, 2)->nullable();
            $obTable->integer('price_type_id')->unsigned()->nullable();
            $obTable->timestamps();

            $obTable->index('item_id');
            $obTable->index('item_type');
            $obTable->index('price');
            $obTable->index('old_price');
            $obTable->index('price_type_id');
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
