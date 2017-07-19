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
        if(Schema::hasTable('lovata_shopaholic_offers')) {
            return;
        }

        Schema::create('lovata_shopaholic_offers', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->boolean('active')->default(0);
            $table->integer('product_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('external_id')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('old_price', 15, 2)->nullable();
            $table->integer('quantity')->unsigned()->default(0);
            $table->text('preview_text')->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('name');
            $table->index('code');
            $table->index('external_id');
            $table->index('product_id');
            $table->index('price');
            $table->index('old_price');
            $table->index('quantity');
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