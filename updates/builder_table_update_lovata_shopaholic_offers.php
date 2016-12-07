<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLovataShopaholicOffers extends Migration
{
    public function up()
    {
        Schema::table('lovata_shopaholic_offers', function($table)
        {
            $table->text('preview_text')->nullable();
            $table->text('description')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('lovata_shopaholic_offers', function($table)
        {
            $table->dropColumn('preview_text');
            $table->dropColumn('description');
        });
    }
}
