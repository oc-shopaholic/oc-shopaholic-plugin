<?php namespace Lovata\Shopaholic\Classes\Event;

use Lovata\Toolbox\Classes\Event\ModelHandler;

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
    protected $obListStore;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        parent::subscribe($obEvent);

        $obEvent->listen('shopaholic.category.update.sorting', function () {
            $this->obListStore->top_level->clear();

            //Get category ID list
            $arCategoryIDList = Category::lists('id');
            if (empty($arCategoryIDList)) {
                return;
            }

            //Clear cache for all categories
            foreach ($arCategoryIDList as $iCategoryID) {
                CategoryItem::clearCache($iCategoryID);
            }
        });
    }

    /**
     * After save event handler
     */
    protected function afterSave()
    {
        parent::afterSave();
        $this->obListStore->top_level->clear();
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        $this->obListStore->top_level->clear();

        //Clear parent item cache
        if (!empty($this->obElement->parent_id)) {
            CategoryItem::clearCache($this->obElement->parent_id);
        }
    }

    /**
     * Init store objects
     */
    protected function init()
    {
        $this->obListStore = CategoryListStore::instance();
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
}
