<?php namespace Lovata\Shopaholic\Controllers;

use Yaml;
use Backend\Classes\Controller;
use BackendMenu;
use Lovata\Shopaholic\Models\Settings;

/**
 * Class Brands
 * @package Lovata\Shopaholic\Controllers
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Brands extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController',
    ];
    
    public $listConfig;
    public $formConfig;
    public $reorderConfig = 'config_reorder.yaml';

    /**
     * Brands constructor.
     */
    public function __construct()
    {
        $this->getListConfig();
        $this->getFormConfig();

        parent::__construct();
        BackendMenu::setContext('Lovata.Shopaholic', 'shopaholic-menu-main', 'shopaholic-menu-brands');
    }

    //TODO:: Пересмотреть удаление полей

    /**
     * Get $listConfig
     */
    protected function getListConfig()
    {
        //Get controller config
        $arConfig = Yaml::parseFile(__DIR__.'/brands/config_list.yaml');

        //Get model config
        $arConfig['list'] = Yaml::parseFile(base_path().'/plugins/lovata/shopaholic/models/brand/columns.yaml');

        //Hide fields
        $arConfiguredViewFields = self::getConfiguredBackendFields();
        if(!empty($arConfiguredViewFields)) {
            foreach($arConfiguredViewFields as $sFieldKey => $sFieldName) {
                if(isset($arConfig['list']['columns'][$sFieldKey]) && Settings::getValue('brand_'.$sFieldKey)) {
                    unset($arConfig['list']['columns'][$sFieldKey]);
                }
            }
        }

        $this->listConfig = ['list' => $arConfig];
    }

    /**
     * Get $formConfig
     */
    protected function getFormConfig()
    {
        //Get controller config
        $arConfig = Yaml::parseFile(__DIR__.'/brands/config_form.yaml');

        //Get model config
        $arConfig['form'] = Yaml::parseFile(base_path().'/plugins/lovata/shopaholic/models/brand/fields.yaml');

        //Hide fields
        $arConfiguredViewFields = self::getConfiguredBackendFields();
        if(!empty($arConfiguredViewFields)) {
            foreach($arConfiguredViewFields as $sFieldKey => $sFieldName) {
                if(isset($arConfig['form']['tabs']['fields'][$sFieldKey]) && Settings::getValue('brand_'.$sFieldKey)) {
                    unset($arConfig['form']['tabs']['fields'][$sFieldKey]);
                }
            }
        }

        $this->formConfig = $arConfig;
    }

    /**
     * Get fields list for backend interface with switching visibility
     * @return array
     */
    public static function getConfiguredBackendFields()
    {
        return [
            'code'                  => 'lovata.toolbox::lang.field.code',
            'external_id'           => 'lovata.toolbox::lang.field.external_id',
            'preview_text'          => 'lovata.toolbox::lang.field.preview_text',
            'description'           => 'lovata.toolbox::lang.field.description',
            'preview_image'         => 'lovata.toolbox::lang.field.preview_image',
            'images'                => 'lovata.toolbox::lang.field.images',
        ];
    }
}