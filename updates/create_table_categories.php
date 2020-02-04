<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTableCategories
 * @package Lovata\Shopaholic\Updates
 */
class CreateTableCategories extends Migration
{
    /**
     * Apply migration
     */
    public function up()
    {
        if (Schema::hasTable('lovata_shopaholic_categories')) {
            return;
        }

        Schema::create('lovata_shopaholic_categories', function (Blueprint $obTable)
        {
            $obTable->engine = 'InnoDB';
            $obTable->increments('id')->unsigned();
            $obTable->boolean('active')->default(0);
            $obTable->string('name');
            $obTable->string('slug')->unique();
            $obTable->string('code')->nullable();
            $obTable->string('external_id')->nullable();
            $obTable->text('preview_text')->nullable();
            $obTable->text('description')->nullable();
            $obTable->integer('parent_id')->nullable()->unsigned();
            $obTable->integer('nest_left')->nullable()->unsigned();
            $obTable->integer('nest_right')->nullable()->unsigned();
            $obTable->integer('nest_depth')->nullable()->unsigned();
            $obTable->timestamps();

            $obTable->index('name');
            $obTable->index('code');
            $obTable->index('external_id');
        });
    }
    
    /**
     * Rollback migration
     */
    public function down()
    {
        Schema::dropIfExists('lovata_shopaholic_categories');
    }
}
