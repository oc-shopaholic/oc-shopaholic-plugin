<?php namespace Lovata\Shopaholic\Updates;

use DB;
use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

use Lovata\Shopaholic\Models\Measure;

/**
 * Class TableCreateMeasure
 * @package Lovata\Shopaholic\Updates
 */
class TableCreateMeasure extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_measure';
    const OLD_TABLE_NAME = 'lovata_properties_shopaholic_measure';

    /**
     * Apply migration
     */
    public function up()
    {
        if (Schema::hasTable(self::TABLE_NAME)) {
            return;
        }

        Schema::create(self::TABLE_NAME, function(Blueprint $obTable)
        {
            $obTable->engine = 'InnoDB';
            $obTable->increments('id');
            $obTable->string('name');
            $obTable->string('code')->nullable();
            $obTable->timestamps();
        });

        if (!Schema::hasTable(self::OLD_TABLE_NAME)) {
            return;
        }

        $obMeasureList = DB::table(self::OLD_TABLE_NAME)->get();
        if ($obMeasureList->isEmpty()) {
            return;
        }

        foreach ($obMeasureList as $obMeasure) {
            Measure::create(['name' => $obMeasure->name]);
        }
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
}