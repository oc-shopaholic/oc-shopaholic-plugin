<?php namespace Lovata\Shopaholic\Classes\Import;

use Lovata\Toolbox\Classes\Helper\AbstractImportModelFromCSV;

use Lovata\Shopaholic\Models\Brand;

/**
 * Class ImportBrandModelFromCSV
 * @package Lovata\Shopaholic\Classes\Import
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ImportBrandModelFromCSV extends AbstractImportModelFromCSV
{
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
    }
}
