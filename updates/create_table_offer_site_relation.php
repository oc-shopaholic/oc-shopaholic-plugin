<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * @package Lovata\Shopaholic\Updates
 */
class CreateTableOfferSiteRelation extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_offer_site_relation';

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
            $obTable->integer('offer_id')->unsigned();
            $obTable->integer('site_id')->unsigned();
            $obTable->primary(['offer_id', 'site_id'], 'offer_site');
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
