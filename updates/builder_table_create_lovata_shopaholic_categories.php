<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLovataShopaholicCategories extends Migration
{
    public function up()
    {
        Schema::create('lovata_shopaholic_categories', function($table)
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
            $table->integer('parent_id')->nullable()->unsigned();
            $table->integer('nest_left')->nullable()->unsigned();
            $table->integer('nest_right')->nullable()->unsigned();
            $table->integer('nest_depth')->nullable()->unsigned();
            $table->timestamps();
            $table->index(['name', 'code']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('lovata_shopaholic_categories');
    }
}