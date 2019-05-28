<?php namespace Lovata\Shopaholic\Classes\Import;

use Lovata\Toolbox\Classes\Helper\AbstractImportModelFromCSV;

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
        $this->arExistIDList = (array) Offer::whereNotNull('external_id')->lists('external_id', 'id');
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
}
