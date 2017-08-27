<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Toolbox\Classes\Collection\ElementCollection;

use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Classes\Store\BrandListStore;

/**
 * Class BrandCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class BrandCollection extends ElementCollection
{
    /** @var BrandListStore */
    protected $obBrandListStore;

    /**
     * BrandCollection constructor.
     * @param BrandListStore $obBrandListStore
     */
    public function __construct(BrandListStore $obBrandListStore)
    {
        $this->obBrandListStore = $obBrandListStore;
        parent::__construct();
    }

    /**
     * Make element item
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\BrandCollection::testCollectionItem()
     * @param int                             $iElementID
     * @param \Lovata\Shopaholic\Models\Brand $obElement
     *
     * @return BrandItem
     */
    protected function makeItem($iElementID, $obElement = null)
    {
        return BrandItem::make($iElementID, $obElement);
    }

    /**
     * Sort list
     * @return $this
     */
    public function sort()
    {
        if(!$this->isClear() && $this->isEmpty()) {
            return $this->returnThis();
        }

        //Get sorting list
        $arElementIDList = $this->obBrandListStore->getBySorting();
        if(empty($arElementIDList)) {
            return $this->clear();
        }

        if($this->isClear()) {
            $this->arElementIDList = $arElementIDList;
            return $this->returnThis();
        }

        $this->arElementIDList = array_intersect($arElementIDList, $this->arElementIDList);
        return $this->returnThis();
    }
    
    /**
     * Apply filter by active product list
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\BrandCollection::testActiveList()
     * @return $this
     */
    public function active()
    {
        $arElementIDList = $this->obBrandListStore->getActiveList();
        return $this->intersect($arElementIDList);
    }

    /**
     * Filter product list by category ID
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\BrandCollection::testCategoryFilter()
     * @param int $iCategoryID
     * @return $this
     */
    public function category($iCategoryID)
    {
        $arElementIDList = $this->obBrandListStore->getByCategory($iCategoryID);
        return $this->intersect($arElementIDList);
    }
}