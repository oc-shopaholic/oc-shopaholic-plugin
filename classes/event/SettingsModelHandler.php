<?php namespace Lovata\Shopaholic\Classes\Event;

use Lovata\Shopaholic\Controllers\Brands;
use Lovata\Shopaholic\Controllers\Categories;
use Lovata\Shopaholic\Controllers\Products;
use Lovata\Shopaholic\Models\Settings;

/**
 * Class SettingsModelHandler
 * @package Lovata\Shopaholic\Classes\Event
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SettingsModelHandler
{
    protected $arFields = [];

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        $obEvent->listen('backend.form.extendFields', SettingsModelHandler::class.'@addFields');
    }

    /**
     * Add backend fields for Settings model
     * @param \Backend\Widgets\Form $obWidget
     */
    public function addFields($obWidget)
    {
        // Only for the Settings controller
        if (!$obWidget->getController() instanceof \System\Controllers\Settings) {
            return;
        }

        // Only for the Settings model
        if (!$obWidget->model instanceof Settings) {
            return;
        }

        $arFields = $this->getAdditionFields();
        if(empty($arFields)) {
            return;
        }

        //Add addition field
        $obWidget->addTabFields($arFields);
    }

    /**
     * Init addition fields config
     * @return array
     */
    protected function getAdditionFields()
    {
        self::addConfiguredFields(Products::getConfiguredBackendFields(), 'product');
        self::addConfiguredFields(Products::getOfferConfiguredBackendFields(), 'offer');
        self::addConfiguredFields(Categories::getConfiguredBackendFields(), 'category');
        self::addConfiguredFields(Brands::getConfiguredBackendFields(), 'brand');

        return $this->arFields;
    }

    /**
     * @param array $arFields
     * @param string $sName
     */
    protected function addConfiguredFields($arFields, $sName)
    {
        if(empty($arFields) || empty($sName)) {
            return;
        }

        $this->arFields[$sName . '_view_off'] = [
            'tab'   => 'lovata.shopaholic::lang.tab.field_view',
            'label' => 'lovata.shopaholic::lang.settings.' . $sName,
            'span'  => 'left',
            'type'  => 'section',
        ];

        foreach($arFields as $sFieldName => $sFieldLabel) {

            if(empty($sFieldName)) {
                continue;
            }

            $this->arFields[$sName . '_' . $sFieldName] = [
                'tab'   => 'lovata.shopaholic::lang.tab.field_view',
                'label' => $sFieldLabel,
                'type'  => 'checkbox',
                'span'  => 'left',
            ];
        }
    }
}