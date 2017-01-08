<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLovataShopaholicProducts extends Migration
{
    public function up()
    {
        Schema::create('lovata_shopaholic_products', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->boolean('active')->default(0);
            $table->integer('brand_id')->nullable()->unsigned();
            $table->integer('category_id')->nullable()->unsigned();
            $table->string('external_id')->nullable();
            $table->string('code')->nullable();
            $table->text('preview_text')->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['name', 'code']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('lovata_shopaholic_products');
    }
}