<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTableCurrency
 * @package Lovata\Shopaholic\Updates
 */
class CreateTableCurrency extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_currency';

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
            $obTable->increments('id')->unsigned();
            $obTable->boolean('active')->default(0);
            $obTable->boolean('is_default')->default(0);
            $obTable->string('name');
            $obTable->string('code')->unique();
            $obTable->string('symbol');
            $obTable->decimal('rate');
            $obTable->string('external_id')->nullable();
            $obTable->integer('sort_order')->nullable();
            $obTable->softDeletes();
            $obTable->timestamps();

            $obTable->index('code');
            $obTable->index('external_id');
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
