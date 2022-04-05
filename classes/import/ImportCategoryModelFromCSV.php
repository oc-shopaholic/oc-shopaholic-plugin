<?php namespace Lovata\Shopaholic\Classes\Import;

use Lovata\Toolbox\Classes\Helper\AbstractImportModelFromCSV;

use Lovata\Shopaholic\Models\Category;

/**
 * Class ImportCategoryModelFromCSV
 * @package Lovata\Shopaholic\Classes\Import
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ImportCategoryModelFromCSV extends AbstractImportModelFromCSV
{
    const MODEL_CLASS = Category::class;

    /** @var Category */
    protected $obParentCategory;

    /** @var Category */
    protected $obModel;

    /**
     * ImportCategoryModelFromCSV constructor.
     */
    public function __construct()
    {
        $this->arExistIDList = (array) Category::whereNotNull('external_id')->pluck('external_id', 'id')->all();
        $this->arExistIDList = array_filter($this->arExistIDList);
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

        parent::prepareImportData();
    }

    /**
     * Process model object after creation/updating
     */
    protected function processModelObject()
    {
        if ($this->obParentCategory === false) {
            $this->obModel->parent_id = null;
            $this->obModel->save();
        } elseif (!empty($this->obParentCategory)) {
            $this->obModel->makeChildOf($this->obParentCategory);
        }

        $this->importPreviewImage();
        $this->importImageList();
    }

    /**
     * Find parent category by external ID and set parent_id
     */
    protected function initParentCategory()
    {
        if (!array_key_exists('parent_id', $this->arImportData)) {
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
}
