<?php namespace Lovata\Shopaholic\Classes\Import;

use Lang;
use Lovata\Toolbox\Classes\Helper\AbstractImportModelFromXML;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\PriceType;
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

    const MODEL_CLASS = Offer::class;

    /** @var Offer */
    protected $obModel;

    protected $bWithTrashed = true;

    /**
     * ImportOfferModelFromXML constructor.
     */
    public function __construct()
    {
        $this->arExistIDList = (array) Offer::whereNotNull('external_id')->pluck('external_id', 'id')->all();
        $this->arExistIDList = array_filter($this->arExistIDList);

        $this->prepareImportSettings();

        parent::__construct();
    }

    /**
     * Get import fields
     * @return array
     */
    public function getFields() : array
    {
        $this->arFieldList = [
            'external_id' => Lang::get('lovata.toolbox::lang.field.external_id'),
            'active'      => Lang::get('lovata.toolbox::lang.field.active'),
            'price'       => Lang::get('lovata.shopaholic::lang.field.price'),
            'old_price'   => Lang::get('lovata.shopaholic::lang.field.old_price'),
            'quantity'    => Lang::get('lovata.shopaholic::lang.field.quantity'),
        ];

        //Get price types
        $arPriceTypeList = (array) PriceType::pluck('name', 'id')->all();
        if (!empty($arPriceTypeList)) {
            foreach ($arPriceTypeList as $iPriceTypeID => $sName) {
                $sKey = 'price_list.'.$iPriceTypeID;
                $this->arFieldList[$sKey.'.price'] = Lang::get('lovata.shopaholic::lang.field.price')." ($sName)";
                $this->arFieldList[$sKey.'.old_price'] = Lang::get('lovata.shopaholic::lang.field.old_price')." ($sName)";
            }
        }

        return parent::getFields();
    }

    /**
     * Start import
     * @param $obProgressBar
     * @throws
     */
    public function import($obProgressBar = null)
    {
        parent::import($obProgressBar);

        $this->deactivateElements();
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
