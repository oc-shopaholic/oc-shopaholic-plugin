<?php namespace Lovata\Shopaholic\Classes\Event\Category;

use Lovata\Toolbox\Models\Settings;
use Lovata\Toolbox\Classes\Event\ModelHandler;

use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Store\CategoryListStore;

/**
 * Class CategoryModelHandler
 * @package Lovata\Shopaholic\Classes\Event\Category
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CategoryModelHandler extends ModelHandler
{
    /** @var  Category */
    protected $obElement;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        parent::subscribe($obEvent);

        Category::extend(function ($obModel) {
            /** @var Category $obModel */
            $bSlugIsTranslatable = Settings::getValue('slug_is_translatable');
            if ($bSlugIsTranslatable) {
                $obModel->translatable[] = ['slug', 'index' => true];
            }
        });

        $obEvent->listen('shopaholic.category.update.sorting', function () {
            CategoryListStore::instance()->top_level->clear();

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
        CategoryListStore::instance()->top_level->clear();

        $this->checkFieldChanges('active', CategoryListStore::instance()->active);
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        CategoryListStore::instance()->top_level->clear();

        //Clear parent item cache
        if (!empty($this->obElement->parent_id)) {
            CategoryItem::clearCache($this->obElement->parent_id);
        }

        if ($this->obElement->active) {
            CategoryListStore::instance()->active->clear();
        }
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
