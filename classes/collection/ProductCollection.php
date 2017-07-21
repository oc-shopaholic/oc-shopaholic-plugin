<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Classes\Store\ProductListStore;

use Lovata\Toolbox\Classes\Collection\ElementCollection;
use Lovata\Toolbox\Traits\Collection\TraitCheckItemActive;
use Lovata\Toolbox\Traits\Collection\TraitCheckItemTrashed;

/**
 * Class ProductCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductCollection extends ElementCollection
{
    use TraitCheckItemActive;
    use TraitCheckItemTrashed;

    /** @var ProductListStore */
    protected $obProductListStore;

    /**
     * ProductCollection constructor.
     * @param ProductListStore $obProductListStore
     */
    public function __construct(ProductListStore $obProductListStore)
    {
        $this->obProductListStore = $obProductListStore;
        parent::__construct();
    }

    /**
     * Make element item
     * @param int   $iElementID
     * @param \Lovata\Shopaholic\Models\Product  $obElement
     *
     * @return ProductItem
     */
    protected function makeItem($iElementID, $obElement = null)
    {
        $obItem = ProductItem::make($iElementID, $obElement);
        $obItem->setCheckingActive($this->bCheckActive);
        $obItem->withTrashed($this->bCheckTrashed);

        return $obItem;
    }

    /**
     * Sort list by
     * @param string $sSorting
     * @return $this
     */
    public function sort($sSorting)
    {
        if(!$this->isClear() && $this->isEmpty()) {
            return $this;
        }

        //Get sorting list
        $arElementIDList = $this->obProductListStore->getBySorting($sSorting);
        if(empty($arElementIDList)) {
            return $this->clear();
        }

        if($this->isClear()) {
            $this->arElementIDList = $arElementIDList;
            return $this;
        }

        $this->arElementIDList = array_intersect($arElementIDList, $this->arElementIDList);
        return $this->returnClone();
    }

    /**
     * Apply filter by active product list0
     * @return $this
     */
    public function active()
    {
        $arElementIDList = $this->obProductListStore->getActiveList();
        return $this->intersect($arElementIDList);
    }

    /**
     * Filter product list by category ID
     * @param int $iCategoryID
     * @return $this
     */
    public function category($iCategoryID)
    {
        $arElementIDList = $this->obProductListStore->getByCategory($iCategoryID);
        return $this->intersect($arElementIDList);
    }

    /**
     * Filter product list by category ID
     * @param int $iBrandID
     * @return $this
     */
    public function brand($iBrandID)
    {
        $arElementIDList = $this->obProductListStore->getByBrand($iBrandID);
        return $this->intersect($arElementIDList);
    }
}