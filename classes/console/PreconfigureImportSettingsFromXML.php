<?php namespace Lovata\Shopaholic\Classes\Console;

use DB;
use Illuminate\Console\Command;

/**
 * Class PreconfigureImportSettingsFromXML
 * @package Lovata\Shopaholic\Classes\Console
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class PreconfigureImportSettingsFromXML extends Command
{
    /**
     * @var string command name.
     */
    protected $name = 'shopaholic:preconfigure_import_from_xml';

    /**
     * @var string The console command description.
     */
    protected $description = 'Preconfigure import settings form xml file';

    protected $arPresetList = [
        [
            'label'  => '1C:Enterprise',
            'config' => '{"file_list":[{"path":"temp\/import\/import.xml"},{"path":"temp\/import\/offers.xml"}],"image_folder":"temp\/import","product":[{"field":"external_id","path_to_field":"\u0418\u0434"},{"field":"name","path_to_field":"\u041d\u0430\u0438\u043c\u0435\u043d\u043e\u0432\u0430\u043d\u0438\u0435"},{"field":"code","path_to_field":"\u0428\u0442\u0440\u0438\u0445\u043a\u043e\u0434"},{"field":"category_id","path_to_field":"\u0413\u0440\u0443\u043f\u043f\u044b"},{"field":"additional_category","path_to_field":"\u0413\u0440\u0443\u043f\u043f\u044b"},{"field":"preview_image","path_to_field":"\u041a\u0430\u0440\u0442\u0438\u043d\u043a\u0430"},{"field":"images","path_to_field":"\u041a\u0430\u0440\u0442\u0438\u043d\u043a\u0430"}],"product_file_path":"0","product_path_to_list":"\u041a\u0430\u0442\u0430\u043b\u043e\u0433\/\u0422\u043e\u0432\u0430\u0440\u044b\/\u0422\u043e\u0432\u0430\u0440","brand_file_path":"","brand_path_to_list":"","brand":[],"brand_deactivate":"0","category_file_path":"0","category_path_to_list":"\u041a\u043b\u0430\u0441\u0441\u0438\u0444\u0438\u043a\u0430\u0442\u043e\u0440\/\u0413\u0440\u0443\u043f\u043f\u044b\/\u0413\u0440\u0443\u043f\u043f\u0430","category_deactivate":"0","category":[{"field":"external_id","path_to_field":"\u0418\u0434"},{"field":"name","path_to_field":"\u041d\u0430\u0438\u043c\u0435\u043d\u043e\u0432\u0430\u043d\u0438\u0435"},{"field":"active","path_to_field":"\u0411\u0438\u0442\u0440\u0438\u043a\u0441\u0410\u043a\u0442\u0438\u0432\u043d\u043e\u0441\u0442\u044c"},{"field":"children","path_to_field":"\u0413\u0440\u0443\u043f\u043f\u044b\/\u0413\u0440\u0443\u043f\u043f\u0430"}],"property_file_path":"0","property_path_to_list":"\u041a\u043b\u0430\u0441\u0441\u0438\u0444\u0438\u043a\u0430\u0442\u043e\u0440\/\u0421\u0432\u043e\u0439\u0441\u0442\u0432\u0430\/\u0421\u0432\u043e\u0439\u0441\u0442\u0432\u043e","property_deactivate":"0","property":[{"field":"external_id","path_to_field":"\u0418\u0434"},{"field":"name","path_to_field":"\u041d\u0430\u0438\u043c\u0435\u043d\u043e\u0432\u0430\u043d\u0438\u0435"}],"property_import_enable":"1","brand_import_enable":"0","category_import_enable":"1","product_import_enable":"1","offer_import_enable":"1","offer_file_path":"1","offer_path_to_list":"\u041f\u0430\u043a\u0435\u0442\u041f\u0440\u0435\u0434\u043b\u043e\u0436\u0435\u043d\u0438\u0439\/\u041f\u0440\u0435\u0434\u043b\u043e\u0436\u0435\u043d\u0438\u044f\/\u041f\u0440\u0435\u0434\u043b\u043e\u0436\u0435\u043d\u0438\u0435","offer":[{"field":"external_id","path_to_field":"\u0418\u0434"},{"field":"product_id","path_to_field":"\u0418\u0434"},{"field":"code","path_to_field":"\u0428\u0442\u0440\u0438\u0445\u043a\u043e\u0434"},{"field":"name","path_to_field":"\u041d\u0430\u0438\u043c\u0435\u043d\u043e\u0432\u0430\u043d\u0438\u0435"},{"field":"preview_image","path_to_field":"\u041a\u0430\u0440\u0442\u0438\u043d\u043a\u0430"},{"field":"images","path_to_field":"\u041a\u0430\u0440\u0442\u0438\u043d\u043a\u0430"},{"field":"quantity","path_to_field":"\u041e\u0441\u0442\u0430\u0442\u043a\u0438\/\u041e\u0441\u0442\u0430\u0442\u043e\u043a\/\u0421\u043a\u043b\u0430\u0434\/\u041a\u043e\u043b\u0438\u0447\u0435\u0441\u0442\u0432\u043e"},{"field":"price","path_to_field":"\u0426\u0435\u043d\u044b\/\u0426\u0435\u043d\u0430\/\u0426\u0435\u043d\u0430\u0417\u0430\u0415\u0434\u0438\u043d\u0438\u0446\u0443"}],"price_import_enable":"1","price_file_path":"1","price_path_to_list":"\u041f\u0430\u043a\u0435\u0442\u041f\u0440\u0435\u0434\u043b\u043e\u0436\u0435\u043d\u0438\u0439\/\u041f\u0440\u0435\u0434\u043b\u043e\u0436\u0435\u043d\u0438\u044f\/\u041f\u0440\u0435\u0434\u043b\u043e\u0436\u0435\u043d\u0438\u0435","price":[{"field":"external_id","path_to_field":"\u0418\u0434"},{"field":"quantity","path_to_field":"\u041e\u0441\u0442\u0430\u0442\u043a\u0438\/\u041e\u0441\u0442\u0430\u0442\u043e\u043a\/\u0421\u043a\u043b\u0430\u0434\/\u041a\u043e\u043b\u0438\u0447\u0435\u0441\u0442\u0432\u043e"},{"field":"price","path_to_field":"\u0426\u0435\u043d\u044b\/\u0426\u0435\u043d\u0430\/\u0426\u0435\u043d\u0430\u0417\u0430\u0415\u0434\u0438\u043d\u0438\u0446\u0443"}],"product_property_list_path":"\u0417\u043d\u0430\u0447\u0435\u043d\u0438\u044f\u0421\u0432\u043e\u0439\u0441\u0442\u0432\/\u0417\u043d\u0430\u0447\u0435\u043d\u0438\u044f\u0421\u0432\u043e\u0439\u0441\u0442\u0432\u0430","product_property_id_path":"\u0418\u0434","product_property_value_path":"\u0417\u043d\u0430\u0447\u0435\u043d\u0438\u0435","offer_property_list_path":"\u0417\u043d\u0430\u0447\u0435\u043d\u0438\u044f\u0421\u0432\u043e\u0439\u0441\u0442\u0432\/\u0417\u043d\u0430\u0447\u0435\u043d\u0438\u044f\u0421\u0432\u043e\u0439\u0441\u0442\u0432\u0430","offer_property_id_path":"\u0418\u0434","offer_property_value_path":"\u0417\u043d\u0430\u0447\u0435\u043d\u0438\u0435"}',
        ],
    ];

    /**
     * Execute the console command.
     * @throws \Throwable
     */
    public function handle()
    {
        $arSettings = [];
        $sPresetName = $this->choice('Select preset with import settings', array_pluck($this->arPresetList, 'label'), 0);
        foreach ($this->arPresetList as $arPresetData) {
            if ($arPresetData['label'] != $sPresetName) {
                continue;
            }

            $arSettings = $arPresetData['config'];
        }

        if (empty($arSettings)) {
            return;
        }

        DB::table('system_settings')->where('item', 'lovata_shopaholic_xml_import_settings')->delete();
        DB::table('system_settings')->insert([
            'value' => $arSettings,
            'item'  => 'lovata_shopaholic_xml_import_settings',
        ]);

        $this->call('cache:clear');
    }
}
