<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Toolbox\Classes\Collection\ElementCollection;

use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Classes\Store\BrandListStore;

/**
 * Class BrandCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @see \Lovata\Shopaholic\Tests\Unit\Collection\BrandCollectionTest
 * @link https://github.com/lovata/oc-shopaholic-plugin/wiki/BrandCollection
 *
 * Search for Shopaholic, Sphinx for Shopaholic
 * @method $this search(string $sSearch)
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
     * Sort list
     * @return $this
     */
    public function sort()
    {
        if (!$this->isClear() && $this->isEmpty()) {
            return $this->returnThis();
        }

        //Get sorting list
        $arResultIDList = $this->obBrandListStore->getBySorting();

        return $this->applySorting($arResultIDList);
    }

    /**
     * Apply filter by active product list
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\BrandCollectionTest::testActiveList()
     * @return $this
     */
    public function active()
    {
        $arResultIDList = $this->obBrandListStore->getActiveList();

        return $this->intersect($arResultIDList);
    }

    /**
     * Filter product list by category ID
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\BrandCollectionTest::testCategoryFilter()
     * @param int $iCategoryID
     * @return $this
     */
    public function category($iCategoryID)
    {
        $arResultIDList = $this->obBrandListStore->getByCategory($iCategoryID);

        return $this->intersect($arResultIDList);
    }

    /**
     * Make element item
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\BrandCollectionTest::testCollectionItem()
     * @param int                             $iElementID
     * @param \Lovata\Shopaholic\Models\Brand $obElement
     *
     * @return BrandItem
     */
    protected function makeItem($iElementID, $obElement = null)
    {
        return BrandItem::make($iElementID, $obElement);
    }
}
