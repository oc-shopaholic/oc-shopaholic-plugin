<?php namespace Lovata\Shopaholic\Classes\Event\Tax;

use System\Classes\PluginManager;
use Lovata\Toolbox\Classes\Event\ModelHandler;

use Lovata\Shopaholic\Models\Tax;
use Lovata\Shopaholic\Classes\Item\TaxItem;
use Lovata\Shopaholic\Classes\Store\TaxListStore;

/**
 * Class TaxModelHandler
 * @package Lovata\Shopaholic\Classes\Event\Tax
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class TaxModelHandler extends ModelHandler
{
    /** @var Tax */
    protected $obElement;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        parent::subscribe($obEvent);

        $obEvent->listen('shopaholic.tax.update.sorting', function () {
            $this->clearSortingList();
        });

        if (PluginManager::instance()->hasPlugin('RainLab.Location')) {
            return;
        }

        Tax::extend(function ($obTax) {
            /** @var Tax $obTax */
            unset($obTax->belongsToMany['country']);
            unset($obTax->belongsToMany['state']);
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

        $this->checkFieldChanges('active', TaxListStore::instance()->active);
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        $this->clearSortingList();

        if ($this->obElement->active) {
            TaxListStore::instance()->active->clear();
        }
    }

    /**
     * Clear sorting list
     */
    protected function clearSortingList()
    {
        TaxListStore::instance()->sorting->clear();
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return Tax::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return TaxItem::class;
    }
}
