<?php namespace Lovata\Shopaholic\Classes\Import;

use Lovata\Toolbox\Classes\Helper\AbstractImportModelFromCSV;

use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Product;

/**
 * Class ImportProductModelFromCSV
 * @package Lovata\Shopaholic\Classes\Import
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ImportProductModelFromCSV extends AbstractImportModelFromCSV
{
    const MODEL_CLASS = Product::class;

    /** @var Product */
    protected $obModel;

    protected $bWithTrashed = true;
    protected $arAdditionalCategoryList = null;

    /**
     * ImportProductModelFromCSV constructor.
     */
    public function __construct()
    {
        $this->arExistIDList = (array) Product::whereNotNull('external_id')->pluck('external_id', 'id')->all();
        $this->arExistIDList = array_filter($this->arExistIDList);
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
        $sCategoryList = array_get($this->arImportData, 'additional_category');
        if ($sCategoryList === null) {
            return;
        }

        $arCategoryIDList = explode(',', $sCategoryList);
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

        $this->arAdditionalCategoryList = (array) Category::whereIn('external_id', $arCategoryIDList)->pluck('id')->all();
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
}
