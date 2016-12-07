<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLovataShopaholicProducts extends Migration
{
    public function up()
    {
        Schema::table('lovata_shopaholic_products', function($table)
        {
            $table->text('preview_text')->nullable()->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('lovata_shopaholic_products', function($table)
        {
            $table->string('preview_text', 255)->nullable()->unsigned(false)->default(null)->change();
        });
    }
}
