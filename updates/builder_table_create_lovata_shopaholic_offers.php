<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLovataShopaholicOffers extends Migration
{
    public function up()
    {
        Schema::create('lovata_shopaholic_offers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('product_id')->unsigned()->nullable();
            $table->boolean('active')->default(0);
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('external_id')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('old_price', 15, 2)->nullable();
            $table->integer('quantity')->unsigned()->default(0);
            $table->text('preview_text')->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['name', 'code']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('lovata_shopaholic_offers');
    }
}