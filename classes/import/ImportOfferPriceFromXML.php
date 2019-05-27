<?php namespace Lovata\Shopaholic\Classes\Import;

use Lang;
use Lovata\Toolbox\Classes\Helper\AbstractImportModelFromXML;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\XmlImportSettings;

/**
 * Class ImportOfferPriceFromXML
 * @package Lovata\Shopaholic\Classes\Import
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ImportOfferPriceFromXML extends AbstractImportModelFromXML
{
    const EXTEND_FIELD_LIST = 'shopaholic.price.extend_xml_import_fields';
    const EXTEND_IMPORT_DATA = 'shopaholic.price.extend_xml_import_data';

    /** @var Offer */
    protected $obModel;

    protected $bWithTrashed = true;

    /**
     * ImportOfferModelFromXML constructor.
     */
    public function __construct()
    {
        $this->arExistIDList = (array) Offer::whereNotNull('external_id')->lists('external_id', 'id');
        $this->arExistIDList = array_filter($this->arExistIDList);

        $this->prepareImportSettings();
        $this->openMainFile();
    }

    /**
     * Get import fields
     * @return array
     */
    public static function getFields() : array
    {
        $arFieldList = [
            'external_id' => Lang::get('lovata.toolbox::lang.field.external_id'),
            'active'      => Lang::get('lovata.toolbox::lang.field.active'),
            'price'       => Lang::get('lovata.shopaholic::lang.field.price'),
            'old_price'   => Lang::get('lovata.shopaholic::lang.field.old_price'),
            'quantity'    => Lang::get('lovata.shopaholic::lang.field.quantity'),
        ];

        $arFieldList = self::extendImportFields($arFieldList);

        return $arFieldList;
    }

    /**
     * Get model class
     * @return string
     */
    protected function getModelClass() : string
    {
        return Offer::class;
    }

    /**
     * Prepare array of import data
     */
    protected function prepareImportData()
    {
        $this->setActiveField();
        $this->setQuantityField();

        parent::prepareImportData();
    }

    /**
     * Set quantity field value
     */
    protected function setQuantityField()
    {
        $iQuantity = array_get($this->arImportData, 'quantity');
        if ($iQuantity === null) {
            return;
        }

        $iQuantity = (int) $iQuantity;
        if ($iQuantity < 0) {
            $iQuantity = 0;
        }

        $this->arImportData['quantity'] = $iQuantity;
    }

    /**
     * Prepare import settings
     */
    protected function prepareImportSettings()
    {
        $this->arXMLFileList = XmlImportSettings::getValue('file_list');
        $this->sImageFolderPath = XmlImportSettings::getValue('image_folder');
        $this->sImageFolderPath = trim($this->sImageFolderPath, '/');

        $this->bDeactivate = (bool) XmlImportSettings::getValue('price_deactivate');
        $this->arImportSettings = XmlImportSettings::getValue('price');
        $this->sElementListPath = XmlImportSettings::getValue('price_path_to_list');

        $iFileNumber = XmlImportSettings::getValue('price_file_path');
        if ($iFileNumber !== null) {
            $this->sMainFilePath = array_get($this->arXMLFileList, $iFileNumber.'.path');
            $this->sMainFilePath = trim($this->sMainFilePath, '/');
        }
    }
}
