<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Shopaholic\Classes\Store\ProductListStore;
use Lovata\Toolbox\Classes\ElementCollection;
use Lovata\Shopaholic\Classes\Item\ProductItem;

/**
 * Class ProductCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ProductCollection extends ElementCollection
{
    /** @var ProductListStore */
    protected $obProductListStore;

    /**
     * ProductCollection constructor.
     * @param ProductListStore $obProductListStore
     */
    public function __construct(ProductListStore $obProductListStore)
    {
        $this->obProductListStore = $obProductListStore;
    }

    /**
     * Sort list by
     * @param string $sSorting
     * @return $this
     */
    public function sortBy($sSorting)
    {
        //Get sorting list
        $arElementIDList = $this->obProductListStore->getBySorting($sSorting);
        return $this->intersect($arElementIDList);
    }

    /**
     * Apply filter by active product list0
     * @return $this
     */
    public function active()
    {
        $this->arElementIDList = $this->obProductListStore->getActiveList();
        return $this;
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
     * Get product model store list
     * @param array $arSettings
     *
     * @return array|null|ProductItem[]
     */
    public function getList($arSettings = [])
    {
        if(empty($this->arElementIDList)) {
            return null;
        }

        $arResult = [];
        foreach ($this->arElementIDList as $iElementID) {

            $obProductStore = ProductItem::make($iElementID, null, $arSettings);
            if($obProductStore->isEmpty()) {
                continue;
            }

            $arResult[$iElementID] = $obProductStore;
        }

        return $arResult;
    }
}