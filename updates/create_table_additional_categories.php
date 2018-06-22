<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTableAdditionalCategories
 * @package Lovata\Shopaholic\Updates
 */
class CreateTableAdditionalCategories extends Migration
{
    /**
     * Apply migration
     */
    public function up()
    {
        if (Schema::hasTable('lovata_shopaholic_additional_categories')) {
            return;
        }

        Schema::create('lovata_shopaholic_additional_categories', function (Blueprint $obTable)
        {
            $obTable->engine = 'InnoDB';
            $obTable->integer('category_id')->unsigned();
            $obTable->integer('product_id')->unsigned();
            $obTable->primary(['category_id', 'product_id'], 'product_category');
        });
    }
    
    /**
     * Rollback migration
     */
    public function down()
    {
        Schema::dropIfExists('lovata_shopaholic_additional_categories');
    }
}
