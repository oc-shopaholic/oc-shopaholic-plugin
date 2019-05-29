<?php namespace Lovata\Shopaholic\Widgets;

use Lang;
use Flash;
use Input;
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
    protected $iCreatedCount = 0;
    protected $iUpdatedCount = 0;
    protected $iSkippedCount = 0;
    protected $iProcessedCount = 0;

    protected $arClassList = [
        'import-brands'     => ImportBrandModelFromXML::class,
        'import-categories' => ImportCategoryModelFromXML::class,
        'import-properties' => 'Lovata\PropertiesShopaholic\Classes\Import\ImportPropertyModelFromXML',
        'import-products'   => ImportProductModelFromXML::class,
        'import-offers'     => ImportOfferModelFromXML::class,
        'import-prices'     => ImportOfferPriceFromXML::class,
    ];

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
    public function onImportFromXML()
    {
        foreach ($this->arClassList as $sKey => $sImportClass) {
            $bEnableImport = (bool) Input::get($sKey);
            if (!class_exists($sImportClass) || !$bEnableImport) {
                continue;
            }

            /** @var \Lovata\Toolbox\Classes\Helper\AbstractImportModelFromXML $obImport */
            $obImport = new $sImportClass();
            $obImport->import();

            $this->iCreatedCount += $obImport->getCreatedCount();
            $this->iUpdatedCount += $obImport->getUpdatedCount();
            $this->iSkippedCount += $obImport->getSkippedCount();
            $this->iProcessedCount += $obImport->getProcessedCount();
        }

        $arReportData = [
            'created'   => $this->iCreatedCount,
            'updated'   => $this->iUpdatedCount,
            'skipped'   => $this->iSkippedCount,
            'processed' => $this->iProcessedCount,
        ];

        Flash::info(Lang::get('lovata.toolbox::lang.message.import_from_xml_report', $arReportData));
    }
}
