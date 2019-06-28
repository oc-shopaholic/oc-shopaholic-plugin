<?php namespace Lovata\Shopaholic\Classes\Import;

use Lang;
use Lovata\Toolbox\Classes\Helper\AbstractImportModelFromXML;

use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Models\XmlImportSettings;

/**
 * Class ImportBrandModelFromXML
 * @package Lovata\Shopaholic\Classes\Import
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ImportBrandModelFromXML extends AbstractImportModelFromXML
{
    const EXTEND_FIELD_LIST = 'shopaholic.brand.extend_xml_import_fields';
    const EXTEND_IMPORT_DATA = 'shopaholic.brand.extend_xml_import_data';

    const MODEL_CLASS = Brand::class;

    /** @var Brand */
    protected $obModel;

    /**
     * ImportBrandModelFromCSV constructor.
     */
    public function __construct()
    {
        $this->arExistIDList = (array) Brand::whereNotNull('external_id')->lists('external_id', 'id');
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
     * Prepare import settings
     */
    protected function prepareImportSettings()
    {
        $this->arXMLFileList = XmlImportSettings::getValue('file_list');
        $this->sImageFolderPath = XmlImportSettings::getValue('image_folder');
        $this->sImageFolderPath = trim($this->sImageFolderPath, '/');

        $this->bDeactivate = (bool) XmlImportSettings::getValue('brand_deactivate');
        $this->arImportSettings = XmlImportSettings::getValue('brand');
        $this->sElementListPath = XmlImportSettings::getValue('brand_path_to_list');

        $iFileNumber = XmlImportSettings::getValue('brand_file_path');
        if ($iFileNumber !== null) {
            $this->sMainFilePath = array_get($this->arXMLFileList, $iFileNumber.'.path');
            $this->sMainFilePath = trim($this->sMainFilePath, '/');
        }
    }
}
