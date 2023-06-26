<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * @package Lovata\Shopaholic\Updates
 */
class CreateTableEntitySiteRelation extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_entity_site_relation';

    /**
     * Apply migration
     */
    public function up()
    {
        if (Schema::hasTable(self::TABLE_NAME)) {
            return;
        }

        Schema::create(self::TABLE_NAME, function (Blueprint $obTable) {
            $obTable->engine = 'InnoDB';
            $obTable->integer('entity_id')->unsigned();
            $obTable->string('entity_type');
            $obTable->integer('site_id')->unsigned();
            $obTable->primary(['entity_id', 'entity_type', 'site_id'], 'entity_site');
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
