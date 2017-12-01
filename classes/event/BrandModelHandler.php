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

    /**
     * BrandModelHandler constructor.
     *
     * @param BrandListStore $obBrandListStore
     */
    public function __construct(BrandListStore $obBrandListStore)
    {
        $this->obListStore = $obBrandListStore;
    }

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

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        $this->clearSortingList();
    }

    /**
     * Clear sorting list
     */
    protected function clearSortingList()
    {
        $this->obListStore->clearSortingList();
    }
}
