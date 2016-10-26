<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLovataShopaholicBrands extends Migration
{
    public function up()
    {
        Schema::create('lovata_shopaholic_brands', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->boolean('active')->default(0);
            $table->string('code')->nullable();
            $table->string('external_id')->nullable();
            $table->text('preview_text')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->nullable();
            $table->timestamps();
            $table->index(['name', 'code']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('lovata_shopaholic_brands');
    }
}