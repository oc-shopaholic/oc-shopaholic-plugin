<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTableBrands
 * @package Lovata\Shopaholic\Updates
 */
class CreateTableBrands extends Migration
{
    /**
     * Apply migration
     */
    public function up()
    {
        if(Schema::hasTable('lovata_shopaholic_brands')) {
            return;
        }

        Schema::create('lovata_shopaholic_brands', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->boolean('active')->default(0);
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->nullable();
            $table->string('external_id')->nullable();
            $table->text('preview_text')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->nullable();
            $table->timestamps();

            $table->index('name');
            $table->index('slug');
            $table->index('code');
            $table->index('external_id');
        });
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        Schema::dropIfExists('lovata_shopaholic_brands');
    }
}