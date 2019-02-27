<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTableTaxCountryRelation
 * @package Lovata\Shopaholic\Updates
 */
class CreateTableTaxCountryRelation extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_tax_country_link';

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
            $obTable->integer('tax_id')->unsigned();
            $obTable->integer('country_id')->unsigned();
            $obTable->primary(['country_id', 'tax_id'], 'tax_country');
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
