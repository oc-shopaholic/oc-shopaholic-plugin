<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class UpdateTableOffersAddDimensionsField
 * @package Lovata\Shopaholic\Updates
 */
class UpdateTableOffersAddDimensionsField extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_offers';

    /**
     * Apply migration
     */
    public function up()
    {
        if (!Schema::hasTable(self::TABLE_NAME)) {
            return;
        }

        $arNewFieldList = [
            'weight',
            'height',
            'length',
            'width',
            'measure_of_unit_id',
            'quantity_in_unit',
        ];

        foreach ($arNewFieldList as $iKey => $sFieldName) {
            if (Schema::hasColumn(self::TABLE_NAME, $sFieldName)) {
                unset($arNewFieldList[$iKey]);
            }
        }

        if (empty($arNewFieldList)) {
            return;
        }

        Schema::table(self::TABLE_NAME, function (Blueprint $obTable) use ($arNewFieldList) {
            if (in_array('quantity_in_unit', $arNewFieldList)) {
                $obTable->double('quantity_in_unit')->nullable()->after('quantity');
            }
            if (in_array('measure_of_unit_id', $arNewFieldList)) {
                $obTable->integer('measure_of_unit_id')->nullable()->after('quantity');
            }
            if (in_array('width', $arNewFieldList)) {
                $obTable->double('width')->nullable()->after('quantity');
            }
            if (in_array('length', $arNewFieldList)) {
                $obTable->double('length')->nullable()->after('quantity');
            }
            if (in_array('height', $arNewFieldList)) {
                $obTable->double('height')->nullable()->after('quantity');
            }
            if (in_array('weight', $arNewFieldList)) {
                $obTable->double('weight')->nullable()->after('quantity');
            }
        });
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        if (!Schema::hasTable(self::TABLE_NAME)) {
            return;
        }

        $arNewFieldList = [
            'weight',
            'height',
            'length',
            'width',
            'measure_of_unit_id',
            'quantity_in_unit',
        ];

        foreach ($arNewFieldList as $iKey => $sFieldName) {
            if (!Schema::hasColumn(self::TABLE_NAME, $sFieldName)) {
                unset($arNewFieldList[$iKey]);
            }
        }

        if (empty($arNewFieldList)) {
            return;
        }

        Schema::table(self::TABLE_NAME, function (Blueprint $obTable) use ($arNewFieldList) {
            $obTable->dropColumn($arNewFieldList);
        });
    }
}
