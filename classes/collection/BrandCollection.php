<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Classes\Store\BrandListStore;
use Lovata\Toolbox\Classes\Collection\ElementCollection;

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
     * @param int   $iElementID
     * @param \Lovata\Shopaholic\Models\Brand  $obElement
     *
     * @return BrandItem
     */
    protected function makeItem($iElementID, $obElement = null)
    {
        return BrandItem::make($iElementID, $obElement);
    }

    /**
     * Apply filter by active product list0
     * @return $this
     */
    public function active()
    {
        $arElementIDList = $this->obBrandListStore->getActiveList();
        return $this->intersect($arElementIDList);
    }

    /**
     * Filter product list by category ID
     * @param int $iCategoryID
     * @return $this
     */
    public function category($iCategoryID)
    {
        $arElementIDList = $this->obBrandListStore->getByCategory($iCategoryID);
        return $this->intersect($arElementIDList);
    }
}