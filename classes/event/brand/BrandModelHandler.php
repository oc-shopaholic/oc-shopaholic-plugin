<?php namespace Lovata\Shopaholic\Classes\Event\Brand;

use Lovata\Toolbox\Models\Settings;
use Lovata\Toolbox\Classes\Event\ModelHandler;

use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Classes\Store\BrandListStore;

/**
 * Class BrandModelHandler
 * @package Lovata\Shopaholic\Classes\Event\Brand
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class BrandModelHandler extends ModelHandler
{
    /** @var Brand */
    protected $obElement;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        parent::subscribe($obEvent);

        Brand::extend(function ($obModel) {
            /** @var Brand $obModel */
            $bSlugIsTranslatable = Settings::getValue('slug_is_translatable');
            if ($bSlugIsTranslatable) {
                $obModel->translatable[] = ['slug', 'index' => true];
            }
        });

        $obEvent->listen('shopaholic.brand.update.sorting', function () {
            $this->clearSortingList();
        });
    }

    /**
     * After create event handler
     */
    protected function afterCreate()
    {
        parent::afterCreate();
        $this->clearSortingList();
    }

    /**
     * After save event handler
     */
    protected function afterSave()
    {
        parent::afterSave();

        $this->checkFieldChanges('active', BrandListStore::instance()->active);
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        $this->clearSortingList();

        if ($this->obElement->active) {
            BrandListStore::instance()->active->clear();
        }
    }

    /**
     * Clear sorting list
     */
    protected function clearSortingList()
    {
        BrandListStore::instance()->sorting->clear();
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return Brand::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return BrandItem::class;
    }
}
