<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTableOffers
 * @package Lovata\Shopaholic\Updates
 */
class CreateTableOffers extends Migration
{
    /**
     * Apply migration
     */
    public function up()
    {
        if (Schema::hasTable('lovata_shopaholic_offers')) {
            return;
        }

        Schema::create('lovata_shopaholic_offers', function (Blueprint $obTable)
        {
            $obTable->engine = 'InnoDB';
            $obTable->increments('id')->unsigned();
            $obTable->boolean('active')->default(0);
            $obTable->integer('product_id')->unsigned()->nullable();
            $obTable->string('name');
            $obTable->string('code')->nullable();
            $obTable->string('external_id')->nullable();
            $obTable->decimal('price', 15, 2)->nullable();
            $obTable->decimal('old_price', 15, 2)->nullable();
            $obTable->integer('quantity')->unsigned()->default(0);
            $obTable->text('preview_text')->nullable();
            $obTable->text('description')->nullable();
            $obTable->softDeletes();
            $obTable->timestamps();

            $obTable->index('name');
            $obTable->index('code');
            $obTable->index('external_id');
            $obTable->index('product_id');
            $obTable->index('price');
            $obTable->index('old_price');
            $obTable->index('quantity');
        });
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        Schema::dropIfExists('lovata_shopaholic_offers');
    }
}
