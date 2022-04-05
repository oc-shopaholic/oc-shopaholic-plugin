<?php namespace Lovata\Shopaholic\Classes\Import;

use Lang;
use Lovata\Toolbox\Classes\Helper\AbstractImportModelFromXML;

use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\XmlImportSettings;

/**
 * Class ImportCategoryModelFromXML
 * @package Lovata\Shopaholic\Classes\Import
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ImportCategoryModelFromXML extends AbstractImportModelFromXML
{
    const EXTEND_FIELD_LIST = 'shopaholic.category.extend_xml_import_fields';
    const EXTEND_IMPORT_DATA = 'shopaholic.category.extend_xml_import_data';
    const PARSE_NODE_CLASS = ParseCategoryXMLNode::class;

    const MODEL_CLASS = Category::class;

    /** @var Category */
    protected $obParentCategory;

    /** @var array */
    protected $arChildrenCategoryList;

    /** @var Category */
    protected $obModel;

    protected $bHasChildrenField = false;

    /**
     * ImportCategoryModelFromCSV constructor.
     */
    public function __construct()
    {
        $this->arExistIDList = (array) Category::whereNotNull('external_id')->pluck('external_id', 'id')->all();
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
            'active'        => Lang::get('lovata.toolbox::lang.field.active'),
            'name'          => Lang::get('lovata.toolbox::lang.field.name'),
            'code'          => Lang::get('lovata.toolbox::lang.field.code'),
            'preview_text'  => Lang::get('lovata.toolbox::lang.field.preview_text'),
            'description'   => Lang::get('lovata.toolbox::lang.field.description'),
            'preview_image' => Lang::get('lovata.toolbox::lang.field.preview_image'),
            'images'        => Lang::get('lovata.toolbox::lang.field.images'),
            'parent_id'     => Lang::get('lovata.toolbox::lang.field.category_parent_id'),
            'children'      => Lang::get('lovata.toolbox::lang.field.children_category'),
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
        $this->initParentCategory();
        $this->setActiveField();

        $this->initPreviewImage();
        $this->initImageList();

        $this->initChildrenCategoryList();

        parent::prepareImportData();
    }

    /**
     * Process model object after creation/updating
     */
    protected function processModelObject()
    {
        if ($this->obParentCategory === false || ($this->bHasChildrenField && empty($this->obParentCategory))) {
            $this->obModel->parent_id = null;
            $this->obModel->save();
        } elseif (!empty($this->obParentCategory)) {
            $this->obModel->makeChildOf($this->obParentCategory);
        }

        $this->importPreviewImage();
        $this->importImageList();

        parent::processModelObject();

        $this->importChildrenCategoryList();
    }

    /**
     * Find parent category by external ID and set parent_id
     */
    protected function initParentCategory()
    {
        if (!array_key_exists('parent_id', $this->arImportData) && !$this->bHasChildrenField) {
            return;
        }

        $iParentID = array_get($this->arImportData, 'parent_id');
        array_forget($this->arImportData, 'parent_id');
        if (empty($iParentID)) {
            $this->obParentCategory = false;

            return;
        }

        //Find parent category
        $this->obParentCategory = Category::getByExternalID($iParentID)->first();
    }

    /**
     * Init children category list
     */
    protected function initChildrenCategoryList()
    {
        if (!array_key_exists('children', $this->arImportData)) {
            return;
        }

        $this->arChildrenCategoryList = array_get($this->arImportData, 'children');
        array_forget($this->arImportData, 'children');
    }

    /**
     * Import children category list
     */
    protected function importChildrenCategoryList()
    {
        if (empty($this->arChildrenCategoryList)) {
            return;
        }

        $iExternalID = $this->obModel->external_id;
        foreach ($this->arChildrenCategoryList as $arCategoryData) {
            $arCategoryData['parent_id'] = $iExternalID;
            $this->importRow($arCategoryData);
        }
    }

    /**
     * Prepare import settings
     */
    protected function prepareImportSettings()
    {
        $this->arXMLFileList = XmlImportSettings::getValue('file_list');
        $this->sImageFolderPath = XmlImportSettings::getValue('image_folder');
        $this->sImageFolderPath = trim($this->sImageFolderPath, '/');

        $this->bDeactivate = (bool) XmlImportSettings::getValue('category_deactivate');
        $this->arImportSettings = (array) XmlImportSettings::getValue('category');
        $this->sElementListPath = XmlImportSettings::getValue('category_path_to_list');

        $iFileNumber = XmlImportSettings::getValue('category_file_path');
        if ($iFileNumber !== null) {
            $this->sMainFilePath = array_get($this->arXMLFileList, $iFileNumber.'.path');
            $this->sPrefix = array_get($this->arXMLFileList, $iFileNumber.'.path_prefix');
            $this->sNamespace = array_get($this->arXMLFileList, $iFileNumber.'.file_namespace');
            $this->sMainFilePath = trim($this->sMainFilePath, '/');
        }

        foreach ($this->arImportSettings as $arFieldData) {
            if (array_get($arFieldData, 'field') == 'children') {
                $this->bHasChildrenField = true;
                break;
            }
        }
    }
}
