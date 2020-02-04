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
        if (Schema::hasTable('lovata_shopaholic_products')) {
            return;
        }

        Schema::create('lovata_shopaholic_products', function (Blueprint $obTable)
        {
            $obTable->engine = 'InnoDB';
            $obTable->increments('id')->unsigned();
            $obTable->boolean('active')->default(0);
            $obTable->string('name');
            $obTable->string('slug')->unique();
            $obTable->integer('brand_id')->nullable()->unsigned();
            $obTable->integer('category_id')->nullable()->unsigned();
            $obTable->string('external_id')->nullable();
            $obTable->string('code')->nullable();
            $obTable->text('preview_text')->nullable();
            $obTable->text('description')->nullable();
            $obTable->softDeletes();
            $obTable->timestamps();

            $obTable->index('name');
            $obTable->index('code');
            $obTable->index('external_id');
            $obTable->index('brand_id');
            $obTable->index('category_id');
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
