<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTablePromoBlock
 * @package Lovata\Shopaholic\Updates
 */
class CreateTablePromoBlock extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_promo_block';

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
            $obTable->boolean('hidden')->default(0);
            $obTable->string('name');
            $obTable->string('slug')->unique();
            $obTable->string('type');
            $obTable->string('code')->nullable();
            $obTable->dateTime('date_begin');
            $obTable->dateTime('date_end')->nullable();
            $obTable->text('preview_text')->nullable();
            $obTable->text('description')->nullable();
            $obTable->integer('sort_order')->nullable();
            $obTable->timestamps();

            $obTable->index('name');
            $obTable->index('code');
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
