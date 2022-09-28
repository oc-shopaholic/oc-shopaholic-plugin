<?php namespace Lovata\Shopaholic\Classes\Import;

use Lang;

use Lovata\Toolbox\Classes\Helper\AbstractImportModelFromXML;

use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\XmlImportSettings;

/**
 * Class ImportProductModelFromXML
 * @package Lovata\Shopaholic\Classes\Import
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ImportProductModelFromXML extends AbstractImportModelFromXML
{
    const EXTEND_FIELD_LIST = 'shopaholic.product.extend_xml_import_fields';
    const EXTEND_IMPORT_DATA = 'shopaholic.product.extend_xml_import_data';

    const MODEL_CLASS = Product::class;

    /** @var Product */
    protected $obModel;

    protected $bWithTrashed = true;
    protected $arAdditionalCategoryList = null;

    /**
     * ImportProductModelFromXML constructor.
     */
    public function __construct()
    {
        $this->arExistIDList = (array) Product::whereNotNull('external_id')->pluck('external_id', 'id')->all();
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
            'external_id'         => Lang::get('lovata.toolbox::lang.field.external_id'),
            'active'              => Lang::get('lovata.toolbox::lang.field.active'),
            'name'                => Lang::get('lovata.toolbox::lang.field.name'),
            'code'                => Lang::get('lovata.toolbox::lang.field.code'),
            'preview_text'        => Lang::get('lovata.toolbox::lang.field.preview_text'),
            'description'         => Lang::get('lovata.toolbox::lang.field.description'),
            'preview_image'       => Lang::get('lovata.toolbox::lang.field.preview_image'),
            'images'              => Lang::get('lovata.toolbox::lang.field.images'),
            'brand_id'            => Lang::get('lovata.shopaholic::lang.field.brand'),
            'category_id'         => Lang::get('lovata.toolbox::lang.field.category'),
            'additional_category' => Lang::get('lovata.shopaholic::lang.field.additional_category'),
        ];

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
        $this->setBrandField();
        $this->setCategoryField();

        $this->initPreviewImage();
        $this->initImageList();
        $this->initAdditionalCategoryField();

        parent::prepareImportData();
    }

    /**
     * Process model object after creation/updating
     */
    protected function processModelObject()
    {
        $this->importPreviewImage();
        $this->importImageList();

        $this->syncAdditionalCategoryList();

        parent::processModelObject();
    }

    /**
     * Set brand_id filed value
     */
    protected function setBrandField()
    {
        $sBrandID = array_get($this->arImportData, 'brand_id');
        if ($sBrandID === null) {
            return;
        }

        if (empty($sBrandID)) {
            $this->arImportData['brand_id'] = null;

            return;
        }

        //Find brand by external ID
        $obBrand = Brand::getByExternalID($sBrandID)->first();
        if (empty($obBrand)) {
            $this->arImportData['brand_id'] = null;
        } else {
            $this->arImportData['brand_id'] = $obBrand->id;
        }
    }

    /**
     * Set category_id filed value
     */
    protected function setCategoryField()
    {
        $sCategoryID = array_get($this->arImportData, 'category_id');
        if ($sCategoryID === null) {
            return;
        }

        if (empty($sCategoryID)) {
            $this->arImportData['category_id'] = null;

            return;
        }

        if (is_array($sCategoryID)) {
            $sCategoryID = array_shift($sCategoryID);
        }

        //Find category by external ID
        $obCategory = Category::getByExternalID($sCategoryID)->first();
        if (empty($obCategory)) {
            $this->arImportData['category_id'] = null;
        } else {
            $this->arImportData['category_id'] = $obCategory->id;
        }
    }

    /**
     * Init additional category ID list
     */
    protected function initAdditionalCategoryField()
    {
        $arCategoryIDList = (array) array_get($this->arImportData, 'additional_category');
        $arCategoryIDList = array_filter($arCategoryIDList);
        if (empty($arCategoryIDList)) {
            return;
        }

        $iMainCategoryID = array_get($this->arImportData, 'category_id');

        array_forget($this->arImportData, 'additional_category');
        foreach ($arCategoryIDList as $iKey => &$iCategoryID) {
            $iCategoryID = trim($iCategoryID);
            if (empty($iCategoryID)) {
                unset($arCategoryIDList[$iKey]);
            }
        }

        if (empty($arCategoryIDList)) {
            $this->arAdditionalCategoryList = [];

            return;
        }

        $this->arAdditionalCategoryList = (array) Category::whereIn('external_id', $arCategoryIDList)->where('id', '!=', $iMainCategoryID)->pluck('id')->all();
    }

    /**
     * Sync link product with additional categories
     */
    protected function syncAdditionalCategoryList()
    {
        if ($this->arAdditionalCategoryList === null) {
            return;
        }

        $this->obModel->additional_category()->sync($this->arAdditionalCategoryList);
    }

    /**
     * Prepare import settings
     */
    protected function prepareImportSettings()
    {
        $this->arXMLFileList = XmlImportSettings::getValue('file_list');
        $this->sImageFolderPath = XmlImportSettings::getValue('image_folder');
        $this->sImageFolderPath = trim($this->sImageFolderPath, '/');

        $this->bDeactivate = (bool) XmlImportSettings::getValue('product_deactivate');
        $this->arImportSettings = XmlImportSettings::getValue('product');
        $this->sElementListPath = XmlImportSettings::getValue('product_path_to_list');

        $iFileNumber = XmlImportSettings::getValue('product_file_path');
        if ($iFileNumber !== null) {
            $this->sMainFilePath = array_get($this->arXMLFileList, $iFileNumber.'.path');
            $this->sPrefix = array_get($this->arXMLFileList, $iFileNumber.'.path_prefix');
            $this->sNamespace = array_get($this->arXMLFileList, $iFileNumber.'.file_namespace');
            $this->sMainFilePath = trim($this->sMainFilePath, '/');
        }
    }
}
