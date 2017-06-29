<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Shopaholic\Classes\Store\CategoryListStore;
use Lovata\Toolbox\Classes\ElementCollection;
use Lovata\Shopaholic\Classes\Item\CategoryItem;

/**
 * Class CategoryCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CategoryCollection extends ElementCollection
{
    /** @var CategoryListStore */
    protected $obCategoryListStore;

    /**
     * CategoryCollection constructor.
     * @param CategoryListStore $obCategoryListStore
     */
    public function __construct(CategoryListStore $obCategoryListStore)
    {
        $this->obCategoryListStore = $obCategoryListStore;
    }

    /**
     * Set to element ID list top level category ID list
     * @return CategoryCollection
     */
    public function tree()
    {
        $this->arElementIDList = $this->obCategoryListStore->getTopLevelList();
        return $this;
    }

    /**
     * Get category item list
     * @return array|null|CategoryItem[]
     */
    public function getList()
    {
        if(empty($this->arElementIDList)) {
            return null;
        }

        $arResult = [];
        foreach ($this->arElementIDList as $iElementID) {

            $obCategoryStore = CategoryItem::make($iElementID);
            if($obCategoryStore->isEmpty()) {
                continue;
            }

            $arResult[$iElementID] = $obCategoryStore;
        }

        return $arResult;
    }
}