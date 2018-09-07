<?php namespace Lovata\Shopaholic\Classes\Store\PromoBlock;

use Event;
use Lovata\Toolbox\Classes\Store\AbstractStoreWithParam;

use Lovata\Shopaholic\Models\PromoBlock;
use Lovata\Shopaholic\Classes\Store\PromoBlockListStore;

/**
 * Class SortingListStore
 * @package Lovata\PromoBlocksShopaholic\Classes\Store\PromoBlock
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * Saving to the cache array with IDs of sorted elements
 * Available sorting values:
 *   - default
 *   - date_begin|asc
 *   - date_begin|desc
 *   - date_end|asc
 *   - date_end|desc
 *
 * Cache data:
 * ['element_id_1', 'element_id_2', ...]
 *
 * Clear cache in:
 * @see \Lovata\Shopaholic\Classes\Event\PromoBlock\PromoBlockModelHandler::afterCreate()
 * @see \Lovata\Shopaholic\Classes\Event\PromoBlock\PromoBlockModelHandler::afterSave()
 * @see \Lovata\Shopaholic\Classes\Event\PromoBlock\PromoBlockModelHandler::afterDelete()
 */
class SortingListStore extends AbstractStoreWithParam
{
    protected static $instance;

    /**
     * Get element ID list from cache or database
     * @param mixed $sFilterValue
     * @return array|null
     */
    public function get($sFilterValue = 'default') : array
    {
        return parent::get($sFilterValue);
    }

    /**
     * Clear element ID list
     * @param mixed $sFilterValue
     */
    public function clear($sFilterValue = 'default')
    {
        parent::clear($sFilterValue);
    }

    /**
     * Get ID list from database
     * @return array
     */
    protected function getIDListFromDB() : array
    {
        switch ($this->sValue) {
            case PromoBlockListStore::SORT_DATE_BEGIN_ASC:
                $arElementIDList = $this->getByDateBeginASC();
                break;
            case PromoBlockListStore::SORT_DATE_BEGIN_DESC:
                $arElementIDList = $this->getByDateBeginDESC();
                break;
            case PromoBlockListStore::SORT_DATE_END_ASC:
                $arElementIDList = $this->getByDateEndASC();
                break;
            case PromoBlockListStore::SORT_DATE_END_DESC:
                $arElementIDList = $this->getByDateEndDESC();
                break;
            case PromoBlockListStore::SORT_DEFAULT:
                $arElementIDList = $this->getBySortOrder();
                break;
            default:
                $arElementIDList = $this->getCustomSortingList();
                break;
        }

        return $arElementIDList;
    }

    /**
     * Get element list with custom sorting
     * @return array
     */
    protected function getCustomSortingList() : array
    {
        $arEventResult = Event::fire('shopaholic.promo_block.sorting.get.list', [$this->sValue]);
        if (empty($arEventResult)) {
            return [];
        }

        $arElementIDList = [];
        foreach ($arEventResult as $arEventPromoBlockIDList) {
            if (empty($arEventPromoBlockIDList) || !is_array($arEventPromoBlockIDList)) {
                continue;
            }

            $arElementIDList = $arEventPromoBlockIDList;
            break;
        }

        return $arElementIDList;
    }

    /**
     * Get discount list with sorting by "date_begin" (asc)
     * @return array
     */
    protected function getByDateBeginASC() : array
    {
        $arElementIDList = (array) PromoBlock::orderBy('date_begin', 'asc')->lists('id');

        return $arElementIDList;
    }

    /**
     * Get discount list with sorting by "date_begin" (desc)
     * @return array
     */
    protected function getByDateBeginDESC() : array
    {
        $arElementIDList = (array) PromoBlock::orderBy('date_begin', 'desc')->lists('id');

        return $arElementIDList;
    }

    /**
     * Get discount list with sorting by "date_end" (asc)
     * @return array
     */
    protected function getByDateEndASC() : array
    {
        $arElementIDList = (array) PromoBlock::orderBy('date_end', 'asc')->lists('id');

        return $arElementIDList;
    }

    /**
     * Get discount list with sorting by "date_end" (desc)
     * @return array
     */
    protected function getByDateEndDESC() : array
    {
        $arElementIDList = (array) PromoBlock::orderBy('date_end', 'desc')->lists('id');

        return $arElementIDList;
    }

    /**
     * Get discount list with sorting by "sort_order" (asc)
     * @return array
     */
    protected function getBySortOrder() :array
    {
        $arElementIDList = (array) PromoBlock::orderBy('sort_order', 'asc')->lists('id');

        return $arElementIDList;
    }
}
