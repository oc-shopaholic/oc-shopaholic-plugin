<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Toolbox\Classes\Collection\ElementCollection;

use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Classes\Store\BrandListStore;

/**
 * Class BrandCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @see     \Lovata\Shopaholic\Tests\Unit\Collection\BrandCollectionTest
 * @link    https://github.com/lovata/oc-shopaholic-plugin/wiki/BrandCollection
 *
 * Search for Shopaholic, Sphinx for Shopaholic
 * @method $this search(string $sSearch)
 */
class BrandCollection extends ElementCollection
{
    const ITEM_CLASS = BrandItem::class;

    /**
     * @var BrandListStore
     */
    protected $obListStore;

    /**
     * BrandCollection constructor.
     */
    public function __construct()
    {
        $this->obListStore = BrandListStore::instance();
        parent::__construct();
    }

    /**
     * Sort list
     * @return $this
     */
    public function sort()
    {
        //Get sorting list
        $arResultIDList = $this->obListStore->sorting->get();

        return $this->applySorting($arResultIDList);
    }

    /**
     * Apply filter by active product list
     * @see \Lovata\Shopaholic\Tests\Unit\Collection\BrandCollectionTest::testActiveList()
     * @return $this
     */
    public function active()
    {
        $arResultIDList = $this->obListStore->active->get();

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
        $arResultIDList = $this->obListStore->category->get($iCategoryID);

        return $this->intersect($arResultIDList);
    }
}
