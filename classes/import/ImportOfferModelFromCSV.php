<?php namespace Lovata\Shopaholic\Classes\Import;

use Lovata\Toolbox\Classes\Helper\AbstractImportModelFromCSV;

use Lovata\Shopaholic\Models\Measure;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;

/**
 * Class ImportOfferModelFromCSV
 * @package Lovata\Shopaholic\Classes\Import
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ImportOfferModelFromCSV extends AbstractImportModelFromCSV
{
    const MODEL_CLASS = Offer::class;

    /** @var Offer */
    protected $obModel;

    protected $bWithTrashed = true;

    /**
     * ImportOfferModelFromCSV constructor.
     */
    public function __construct()
    {
        $this->arExistIDList = (array) Offer::whereNotNull('external_id')->pluck('external_id', 'id')->all();
        $this->arExistIDList = array_filter($this->arExistIDList);
    }

    /**
     * Prepare array of import data
     */
    protected function prepareImportData()
    {
        $this->setActiveField();
        $this->setProductField();
        $this->setQuantityField();
        $this->setMeasureField();
        $this->setMeasureOfUnitField();

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

    /**
     * Set product_id filed value
     */
    protected function setProductField()
    {
        $sProductID = array_get($this->arImportData, 'product_id');
        if ($sProductID === null) {
            return;
        }

        if (empty($sProductID)) {
            $this->arImportData['product_id'] = null;

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
     * Set measure filed value
     */
    protected function setMeasureOfUnitField()
    {
        $sMeasure = array_get($this->arImportData, 'measure_of_unit');
        array_forget($this->arImportData, 'measure_of_unit');
        if ($sMeasure === null) {
            return;
        }

        if (empty($sMeasure)) {
            $this->arImportData['measure_of_unit_id'] = null;
            return;
        }

        $obMeasure = Measure::getByName($sMeasure)->first();
        if (empty($obMeasure)) {
            $obMeasure = Measure::create([
                'name' => $sMeasure,
            ]);
        }

        $this->arImportData['measure_of_unit_id'] = $obMeasure->id;
    }

    /**
     * Set measure filed value
     */
    protected function setMeasureField()
    {
        $sMeasure = array_get($this->arImportData, 'measure_id');
        array_forget($this->arImportData, 'measure_id');
        if ($sMeasure === null) {
            return;
        }

        if (empty($sMeasure)) {
            $this->arImportData['measure_id'] = null;
            return;
        }

        $obMeasure = Measure::getByName($sMeasure)->first();
        if (empty($obMeasure)) {
            $obMeasure = Measure::create([
                'name' => $sMeasure,
            ]);
        }

        $this->arImportData['measure_id'] = $obMeasure->id;
    }
}
