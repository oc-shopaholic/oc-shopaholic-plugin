<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTableProductSlug
 * @package Lovata\Shopaholic\Updates
 */
class CreateTableProductSlugs extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_product_slugs';

    /**
     * Apply migration
     */
    public function up()
    {
        if (Schema::hasTable(self::TABLE_NAME)) {
            return;
        }

        Schema::create(self::TABLE_NAME, function (Blueprint $obTable)
        {
            $obTable->engine = 'InnoDB';
            $obTable->increments('id')->unsigned();
            $obTable->string('source');
            $obTable->string('slug')->unique();
            $obTable->integer('product_id')->unsigned();
            $obTable->timestamps();

            $obTable->index('slug');
            $obTable->index('product_id');
        });
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
}
