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
        Category::extend(function ($obElement) {
            /** @var Category $obElement */
            $obElement->bindEvent('model.afterSave', function () use($obElement) {
                $this->afterSave($obElement);
            });
        });

        Category::extend(function ($obElement) {
            /** @var Category $obElement */
            $obElement->bindEvent('model.afterDelete', function () use($obElement) {
                $this->afterDelete($obElement);
            });
        });

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
        
        CCache::clear([Plugin::CACHE_TAG, CategoryItem::CACHE_TAG_ELEMENT]);
    }

    /**
     * Get fields list for backend interface with switching visibility
     * @return array
     */
    public static function getConfiguredBackendFields()
    {
        return [
            'code'                  => 'lovata.toolbox::lang.field.code',
            'external_id'           => 'lovata.toolbox::lang.field.external_id',
            'preview_text'          => 'lovata.toolbox::lang.field.preview_text',
            'description'           => 'lovata.toolbox::lang.field.description',
            'preview_image'         => 'lovata.toolbox::lang.field.preview_image',
            'images'                => 'lovata.toolbox::lang.field.images',
        ];
    }
}