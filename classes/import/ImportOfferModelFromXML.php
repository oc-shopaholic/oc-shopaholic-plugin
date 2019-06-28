<?php namespace Lovata\Shopaholic\Classes\Import;

use Lang;
use Lovata\Toolbox\Classes\Helper\AbstractImportModelFromXML;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\PriceType;
use Lovata\Shopaholic\Models\XmlImportSettings;

/**
 * Class ImportOfferModelFromXML
 * @package Lovata\Shopaholic\Classes\Import
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ImportOfferModelFromXML extends AbstractImportModelFromXML
{
    const EXTEND_FIELD_LIST = 'shopaholic.offer.extend_xml_import_fields';
    const EXTEND_IMPORT_DATA = 'shopaholic.offer.extend_xml_import_data';

    const MODEL_CLASS = Offer::class;

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

        parent::__construct();
    }

    /**
     * Get import fields
     * @return array
     */
    public function getFields() : array
    {
        $this->arFieldList = [
            'external_id'   => Lang::get('lovata.toolbox::lang.field.external_id'),
            'product_id'    => Lang::get('lovata.shopaholic::lang.field.product_id'),
            'active'        => Lang::get('lovata.toolbox::lang.field.active'),
            'name'          => Lang::get('lovata.toolbox::lang.field.name'),
            'code'          => Lang::get('lovata.toolbox::lang.field.code'),
            'price'         => Lang::get('lovata.shopaholic::lang.field.price'),
            'old_price'     => Lang::get('lovata.shopaholic::lang.field.old_price'),
            'quantity'      => Lang::get('lovata.shopaholic::lang.field.quantity'),
            'preview_text'  => Lang::get('lovata.toolbox::lang.field.preview_text'),
            'description'   => Lang::get('lovata.toolbox::lang.field.description'),
            'preview_image' => Lang::get('lovata.toolbox::lang.field.preview_image'),
            'images'        => Lang::get('lovata.toolbox::lang.field.images'),
        ];

        //Get price types
        $arPriceTypeList = (array) PriceType::lists('name', 'id');
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
        $this->setProductField();
        $this->setQuantityField();

        $this->initPreviewImage();
        $this->initImageList();

        parent::prepareImportData();
    }

    /**
     * Process model object after creation/updating
     */
    protected function processModelObject()
    {
        $this->importPreviewImage();
        $this->importImageList();

        parent::processModelObject();
    }

    /**
     * Set product_id filed value
     */
    protected function setProductField()
    {
        $sProductID = array_get($this->arImportData, 'product_id');
        if ($sProductID === null) {
            return;
        }

        //Find product by external ID
        $obProduct = Product::withTrashed()->getByExternalID($sProductID)->first();
        if (empty($obProduct)) {
            $this->arImportData['product_id'] = null;
        } else {
            $this->arImportData['product_id'] = $obProduct->id;
        }
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

        $this->bDeactivate = (bool) XmlImportSettings::getValue('offer_deactivate');
        $this->arImportSettings = XmlImportSettings::getValue('offer');
        $this->sElementListPath = XmlImportSettings::getValue('offer_path_to_list');

        $iFileNumber = XmlImportSettings::getValue('offer_file_path');
        if ($iFileNumber !== null) {
            $this->sMainFilePath = array_get($this->arXMLFileList, $iFileNumber.'.path');
            $this->sMainFilePath = trim($this->sMainFilePath, '/');
        }
    }
}
