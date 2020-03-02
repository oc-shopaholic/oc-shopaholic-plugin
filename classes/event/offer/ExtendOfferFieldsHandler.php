<?php namespace Lovata\Shopaholic\Classes\Event\Offer;

use Lang;
use Lovata\Shopaholic\Models\Measure;
use Lovata\Shopaholic\Models\Settings;
use Lovata\Toolbox\Classes\Event\AbstractBackendFieldHandler;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Controllers\Offers;

/**
 * Class ExtendOfferFieldsHandler
 * @package Lovata\Shopaholic\Classes\Event\Offer
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ExtendOfferFieldsHandler extends AbstractBackendFieldHandler
{
    protected $iPriority = 15000;

    /**
     * Extend form fields
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function extendFields($obWidget)
    {
        $arAdditionFields = [
            'measure'  => [
                'label'       => 'lovata.shopaholic::lang.field.measure',
                'type'        => 'relation',
                'span'        => 'left',
                'emptyOption' => 'lovata.toolbox::lang.field.empty',
                'tab'         => 'lovata.shopaholic::lang.tab.dimensions',
            ],
            'weight'           => [
                'label' => $this->getWeightFieldLabel(),
                'type'  => 'number',
                'span'  => 'left',
                'tab'   => 'lovata.shopaholic::lang.tab.dimensions',
            ],
            'height'           => [
                'label' => $this->getDimensionsFieldLabel('lovata.toolbox::lang.field.height'),
                'type'  => 'number',
                'span'  => 'left',
                'tab'   => 'lovata.shopaholic::lang.tab.dimensions',
            ],
            'length'           => [
                'label' => $this->getDimensionsFieldLabel('lovata.toolbox::lang.field.length'),
                'type'  => 'number',
                'span'  => 'left',
                'tab'   => 'lovata.shopaholic::lang.tab.dimensions',
            ],
            'width'            => [
                'label' => $this->getDimensionsFieldLabel('lovata.toolbox::lang.field.width'),
                'type'  => 'number',
                'span'  => 'left',
                'tab'   => 'lovata.shopaholic::lang.tab.dimensions',
            ],
            'quantity_in_unit' => [
                'label' => 'lovata.shopaholic::lang.field.quantity_in_unit',
                'type'  => 'number',
                'span'  => 'left',
                'tab'   => 'lovata.shopaholic::lang.tab.dimensions',
            ],
            'measure_of_unit'  => [
                'label'       => 'lovata.shopaholic::lang.field.measure_of_unit',
                'type'        => 'relation',
                'span'        => 'left',
                'emptyOption' => 'lovata.toolbox::lang.field.empty',
                'tab'         => 'lovata.shopaholic::lang.tab.dimensions',
            ],
        ];

        $obWidget->addTabFields($arAdditionFields);
    }

    /**
     * Get weight field label
     * @return string
     */
    protected function getWeightFieldLabel()
    {
        $sLabel = Lang::get('lovata.toolbox::lang.field.weight');
        $iMeasureID = Settings::getValue('weight_measure');
        if (empty($iMeasureID)) {
            return $sLabel;
        }

        $obMeasure = Measure::find($iMeasureID);
        if (empty($obMeasure)) {
            return $sLabel;
        }

        $sLabel .= " ({$obMeasure->name})";

        return $sLabel;
    }

    /**
     * Get dimensions field label
     * @param string $sLangPath
     * @return string
     */
    protected function getDimensionsFieldLabel($sLangPath)
    {
        $sLabel = Lang::get($sLangPath);
        $iMeasureID = Settings::getValue('dimensions_measure');
        if (empty($iMeasureID)) {
            return $sLabel;
        }

        $obMeasure = Measure::find($iMeasureID);
        if (empty($obMeasure)) {
            return $sLabel;
        }

        $sLabel .= " ({$obMeasure->name})";

        return $sLabel;
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass() : string
    {
        return Offer::class;
    }

    /**
     * Get controller class name
     * @return string
     */
    protected function getControllerClass() : string
    {
        return Offers::class;
    }
}
