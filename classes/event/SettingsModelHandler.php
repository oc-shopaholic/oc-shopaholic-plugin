<?php namespace Lovata\Shopaholic\Classes\Event;

use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;
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

        $obEvent->listen('backend.list.extendColumns', function ($obWidget) {
            $this->hideListColumns($obWidget);
        });

        $obEvent->listen('backend.form.extendFields', function ($obWidget) {
            $this->hideListColumns($obWidget);
        });
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
        self::addConfiguredFields($this->getConfiguredBackendFieldsProduct(), 'product');
        self::addConfiguredFields($this->getConfiguredBackendFieldsOffer(), 'offer');
        self::addConfiguredFields($this->getConfiguredBackendFieldsCategory(), 'category');
        self::addConfiguredFields($this->getConfiguredBackendFieldsBrand(), 'brand');

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

    /**
     * Hide backend list columns
     * @param \Backend\Widgets\Lists|\Backend\Widgets\Form $obWidget
     */
    protected function hideListColumns($obWidget)
    {
        $sModelName = get_class($obWidget->model);
        $sModelKey = null;
        
        switch($sModelName) {
            case Product::class :
                $arConfiguredViewFields = $this->getConfiguredBackendFieldsProduct();
                $sModelKey = 'product';
                break;
            case Offer::class :
                $arConfiguredViewFields = $this->getConfiguredBackendFieldsOffer();
                $sModelKey = 'offer';
                break;
            case Category::class :
                $arConfiguredViewFields = $this->getConfiguredBackendFieldsCategory();
                $sModelKey = 'category';
                break;
            case Brand::class :
                $arConfiguredViewFields = $this->getConfiguredBackendFieldsBrand();
                $sModelKey = 'brand';
                break;
        }
        
        if(empty($arConfiguredViewFields)) {
            return;
        }

        foreach($arConfiguredViewFields as $sFieldKey => $sFieldName) {
            if(!Settings::getValue($sModelKey.'_'.$sFieldKey)) {
                continue;
            }

            if($obWidget instanceof \Backend\Widgets\Lists) {
                $obWidget->removeColumn($sFieldKey);
            } else {
                $obWidget->removeField($sFieldKey);
            }
        }
    }

    /**
     * Get fields list for backend interface with switching visibility (Brand)
     * @return array
     */
    private function getConfiguredBackendFieldsBrand()
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

    /**
     * Get fields list for backend interface with switching visibility (Category)
     * @return array
     */
    private function getConfiguredBackendFieldsCategory()
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

    /**
     * Get fields list for backend interface with switching visibility (Offer)
     * @return array
     */
    private function getConfiguredBackendFieldsOffer() {
        return [
            'quantity'              => 'lovata.shopaholic::lang.field.quantity',
            'price'                 => 'lovata.shopaholic::lang.field.price',
            'old_price'             => 'lovata.shopaholic::lang.field.old_price',
            'code'                  => 'lovata.toolbox::lang.field.code',
            'external_id'           => 'lovata.toolbox::lang.field.external_id',
            'preview_text'          => 'lovata.toolbox::lang.field.preview_text',
            'description'           => 'lovata.toolbox::lang.field.description',
            'preview_image'         => 'lovata.toolbox::lang.field.preview_image',
            'images'                => 'lovata.toolbox::lang.field.images',
        ];
    }

    /**
     * Get fields list for backend interface with switching visibility (Product)
     * @return array
     */
    private function getConfiguredBackendFieldsProduct()
    {
        return [
            'code'                  => 'lovata.toolbox::lang.field.code',
            'external_id'           => 'lovata.toolbox::lang.field.external_id',
            'category'              => 'lovata.toolbox::lang.field.category',
            'brand'                 => 'lovata.shopaholic::lang.field.brand',
            'preview_text'          => 'lovata.toolbox::lang.field.preview_text',
            'description'           => 'lovata.toolbox::lang.field.description',
            'preview_image'         => 'lovata.toolbox::lang.field.preview_image',
            'images'                => 'lovata.toolbox::lang.field.images',
        ];
    }
}