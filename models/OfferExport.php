<?php namespace Lovata\Shopaholic\Models;

use System\Classes\PluginManager;

use Lovata\PropertiesShopaholic\Classes\Event\Offer\ExtendOfferControllerHandler;
use Lovata\Toolbox\Classes\Helper\AbstractExportModelInCSV;

/**
 * Class OfferExport
 *
 * @package Lovata\Shopaholic\Models
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 */
class OfferExport extends AbstractExportModelInCSV
{
    const RELATION_MEASURE         = 'measure';
    const RELATION_MEASURE_OF_UNIT = 'measure_of_unit';

    const RELATION_LIST = [
        self::RELATION_MEASURE,
        self::RELATION_MEASURE_OF_UNIT,
    ];

    /** @var string */
    public $table = 'lovata_shopaholic_offers';

    /**
     * Init.
     * @param array|null $arColumns
     */
    protected function init($arColumns)
    {
        parent::init($arColumns);

        $this->initPropertyColumnListForProductOrOffer();
    }

    /**
     * Get property list.
     * @return array
     */
    protected function getPropertyList() : array
    {
        if (!PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            return [];
        }

        $arPropertyList = ExtendOfferControllerHandler::getPropertyListConfig();

        if (empty($arPropertyList)) {
            return [];
        }

        $arPropertyList = array_keys($arPropertyList);

        return $arPropertyList;
    }

    /**
     * Get item list.
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Offer[]
     */
    protected function getItemList()
    {
        return Offer::with($this->arRelationColumnList)->get();
    }

    /**
     * Prepare model relations data.
     * @param Offer $obOffer
     * @return array
     */
    protected function prepareModelRelationsData($obOffer) : array
    {
        $arResult = [];

        if (empty($this->arRelationColumnList)) {
            return $arResult;
        }

        if (!empty($obOffer->measure) && in_array(self::RELATION_MEASURE, $this->arRelationColumnList)) {
            $arResult[self::RELATION_MEASURE] = $obOffer->measure->name;
        }
        if (!empty($obOffer->measure_of_unit)
            && in_array(self::RELATION_MEASURE_OF_UNIT, $this->arRelationColumnList)) {
            $arResult[self::RELATION_MEASURE_OF_UNIT] = $obOffer->measure_of_unit->name;
        }

        return $arResult;
    }

    /**
     * Prepare model properties data.
     * @param Offer $obOffer
     * @return array
     */
    protected function prepareModelPropertiesData($obOffer) : array
    {
        $arResult = [];

        if (empty($this->arPropertyColumnList) || empty($obOffer->property)) {
            return $arResult;
        }

        foreach ($this->arPropertyColumnList as $sKey => $sField) {
            $arResult[$sKey] = array_get($obOffer->property, $sField);
        }

        return $arResult;
    }
}
