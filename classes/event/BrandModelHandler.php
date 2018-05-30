<?php namespace Lovata\Shopaholic\Classes\Event;

use Lovata\Toolbox\Classes\Event\ModelHandler;

use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Classes\Store\BrandListStore;

/**
 * Class BrandModelHandler
 * @package Lovata\Shopaholic\Classes\Event
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class BrandModelHandler extends ModelHandler
{
    /** @var  BrandListStore */
    protected $obListStore;

    /** @var Brand */
    protected $obElement;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        parent::subscribe($obEvent);

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

        $this->checkFieldChanges('active', $this->obListStore->active);
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        $this->clearSortingList();

        if ($this->obElement->active) {
            $this->obListStore->active->clear();
        }
    }

    /**
     * Clear sorting list
     */
    protected function clearSortingList()
    {
        $this->obListStore->sorting->clear();
    }

    /**
     * Init store objects
     */
    protected function init()
    {
        $this->obListStore = BrandListStore::instance();
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
