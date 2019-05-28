<?php namespace Lovata\Shopaholic\Widgets;

use Lang;
use Flash;
use Backend\Classes\ReportWidgetBase;

use Lovata\Shopaholic\Classes\Import\ImportOfferModelFromXML;
use Lovata\Shopaholic\Classes\Import\ImportOfferPriceFromXML;
use Lovata\Shopaholic\Classes\Import\ImportProductModelFromXML;
use Lovata\Shopaholic\Classes\Import\ImportBrandModelFromXML;
use Lovata\Shopaholic\Classes\Import\ImportCategoryModelFromXML;

/**
 * Class ImportFromXML
 * @package Lovata\Shopaholic\Widgets
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ImportFromXML extends ReportWidgetBase
{
    /**
     * Render method
     * @return mixed|string
     * @throws \SystemException
     */
    public function render()
    {
        return $this->makePartial('widget');
    }

    /**
     * Start import from XML
     */
    public function onImportProductsFromXML()
    {
        $obImport = new ImportProductModelFromXML();
        $obImport->import();

        $arReportData = [
            'created'   => $obImport->getCreatedCount(),
            'updated'   => $obImport->getUpdatedCount(),
            'skipped'   => $obImport->getSkippedCount(),
            'processed' => $obImport->getProcessedCount(),
        ];

        Flash::info(Lang::get('lovata.toolbox::lang.message.import_from_xml_report', $arReportData));
    }

    /**
     * Start import from XML
     */
    public function onImportOffersFromXML()
    {
        $obImport = new ImportOfferModelFromXML();
        $obImport->import();

        $arReportData = [
            'created'   => $obImport->getCreatedCount(),
            'updated'   => $obImport->getUpdatedCount(),
            'skipped'   => $obImport->getSkippedCount(),
            'processed' => $obImport->getProcessedCount(),
        ];

        Flash::info(Lang::get('lovata.toolbox::lang.message.import_from_xml_report', $arReportData));
    }

    /**
     * Start import from XML
     */
    public function onImportPricesFromXML()
    {
        $obImport = new ImportOfferPriceFromXML();
        $obImport->import();

        $arReportData = [
            'created'   => $obImport->getCreatedCount(),
            'updated'   => $obImport->getUpdatedCount(),
            'skipped'   => $obImport->getSkippedCount(),
            'processed' => $obImport->getProcessedCount(),
        ];

        Flash::info(Lang::get('lovata.toolbox::lang.message.import_from_xml_report', $arReportData));
    }

    /**
     * Start import from XML
     */
    public function onImportBrandsFromXML()
    {
        $obImport = new ImportBrandModelFromXML();
        $obImport->import();

        $arReportData = [
            'created'   => $obImport->getCreatedCount(),
            'updated'   => $obImport->getUpdatedCount(),
            'skipped'   => $obImport->getSkippedCount(),
            'processed' => $obImport->getProcessedCount(),
        ];

        Flash::info(Lang::get('lovata.toolbox::lang.message.import_from_xml_report', $arReportData));
    }

    /**
     * Start import from XML
     */
    public function onImportCategoriesFromXML()
    {
        $obImport = new ImportCategoryModelFromXML();
        $obImport->import();

        $arReportData = [
            'created'   => $obImport->getCreatedCount(),
            'updated'   => $obImport->getUpdatedCount(),
            'skipped'   => $obImport->getSkippedCount(),
            'processed' => $obImport->getProcessedCount(),
        ];

        Flash::info(Lang::get('lovata.toolbox::lang.message.import_from_xml_report', $arReportData));
    }
}
