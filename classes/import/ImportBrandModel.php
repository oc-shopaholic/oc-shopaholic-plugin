<?php namespace Lovata\Shopaholic\Classes\Import;

use Lovata\Toolbox\Classes\Helper\AbstractImportModel;

use Lovata\Shopaholic\Models\Brand;

/**
 * Class ImportBrandModel
 * @package Lovata\Shopaholic\Classes\Import
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ImportBrandModel extends AbstractImportModel
{
    /** @var Brand */
    protected $obModel;

    /**
     * ImportBrandModel constructor.
     */
    public function __construct()
    {
        $this->arExistIDList = Brand::whereNotNull('external_id')->lists('external_id', 'id');
        $this->arExistIDList = array_filter($this->arExistIDList);
    }

    /**
     * Get model class
     * @return string
     */
    protected function getModelClass() : string
    {
        return Brand::class;
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
