<?php namespace Lovata\Shopaholic\Classes\Event;

use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Store\CategoryListStore;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Plugin;

/**
 * Class CategoryModelHandler
 * @package Lovata\Shopaholic\Classes\Event
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CategoryModelHandler
{
    /** @var  Category */
    protected $obElement;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        $obEvent->listen('shopaholic.category.after.save', CategoryModelHandler::class.'@afterSave');
        $obEvent->listen('shopaholic.category.after.delete', CategoryModelHandler::class.'@afterDelete');
        $obEvent->listen('shopaholic.category.update.sorting', CategoryModelHandler::class.'@clearTopLevelList');
    }

    /**
     * After save event handler
     * @param Category $obElement
     */
    public function afterSave($obElement)
    {
        if(empty($obElement) || !$obElement instanceof Category) {
            return;
        }
        
        $this->obElement = $obElement;
        $this->clearItemCache();
        $this->clearTopLevelList();
    }

    /**
     * After delete event handler
     * @param Category $obElement
     */
    public function afterDelete($obElement)
    {
        if(empty($obElement) || !$obElement instanceof Category) {
            return;
        }

        $this->obElement = $obElement;
        $this->clearItemCache();
        $this->clearTopLevelList();
    }

    /**
     * Clear item cache
     */
    protected function clearItemCache()
    {
        CategoryItem::clearCache($this->obElement->id);
    }
    
    /**
     * Clear top level category ID list
     */
    public function clearTopLevelList()
    {
        $arCacheTags = [Plugin::CACHE_TAG, CategoryListStore::CACHE_TAG_LIST];
        $sCacheKey = CategoryListStore::CACHE_KEY_TOP_LEVEL_LIST;

        CCache::clear($arCacheTags, $sCacheKey);
    }
}