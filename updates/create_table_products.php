<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTableProducts
 * @package Lovata\Shopaholic\Updates
 */
class CreateTableProducts extends Migration
{
    /**
     * Apply migration
     */
    public function up()
    {
        if(Schema::hasTable('lovata_shopaholic_products')) {
            return;
        }

        Schema::create('lovata_shopaholic_products', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->boolean('active')->default(0);
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('brand_id')->nullable()->unsigned();
            $table->integer('category_id')->nullable()->unsigned();
            $table->string('external_id')->nullable();
            $table->string('code')->nullable();
            $table->text('preview_text')->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('name');
            $table->index('slug');
            $table->index('code');
            $table->index('external_id');
            $table->index('brand_id');
            $table->index('category_id');
        });
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        Schema::dropIfExists('lovata_shopaholic_products');
    }
}