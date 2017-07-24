<?php namespace Lovata\Shopaholic\Classes\Event;

use Kharanenka\Helper\CCache;

use Lovata\Toolbox\Classes\Event\ModelHandler;

use Lovata\Shopaholic\Plugin;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Store\CategoryListStore;

/**
 * Class CategoryModelHandler
 * @package Lovata\Shopaholic\Classes\Event
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CategoryModelHandler extends ModelHandler
{
    /** @var  Category */
    protected $obElement;

    /** @var  CategoryListStore */
    protected $obCategoryListStore;

    /**
     * CategoryModelHandler constructor.
     *
     * @param CategoryListStore $obCategoryListStore
     */
    public function __construct(CategoryListStore $obCategoryListStore)
    {
        $this->obCategoryListStore = $obCategoryListStore;
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return Category::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return CategoryItem::class;
    }
    
    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        parent::subscribe($obEvent);
        $obEvent->listen('shopaholic.category.update.sorting', CategoryModelHandler::class.'@clearTopLevelList');
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        $this->obCategoryListStore->clearTopLevelList();
    }
    
    /**
     * Clear top level category ID list
     */
    protected function clearTopLevelList()
    {
        $this->obCategoryListStore->clearTopLevelList();
        CCache::clear([Plugin::CACHE_TAG, CategoryItem::CACHE_TAG_ELEMENT]);
    }
}