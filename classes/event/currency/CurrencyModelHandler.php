<?php namespace Lovata\Shopaholic\Classes\Event\Currency;

use Lovata\Toolbox\Classes\Event\ModelHandler;

use Lovata\Shopaholic\Models\Currency;
use Lovata\Shopaholic\Classes\Item\CurrencyItem;
use Lovata\Shopaholic\Classes\Store\CurrencyListStore;

/**
 * Class CurrencyModelHandler
 * @package Lovata\Shopaholic\Classes\Event\Currency
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CurrencyModelHandler extends ModelHandler
{
    /** @var Currency */
    protected $obElement;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        parent::subscribe($obEvent);

        $obEvent->listen('shopaholic.currency.update.sorting', function () {
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

        $this->checkFieldChanges('active', CurrencyListStore::instance()->active);
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        $this->clearSortingList();

        if ($this->obElement->active) {
            CurrencyListStore::instance()->active->clear();
        }
    }

    /**
     * Clear sorting list
     */
    protected function clearSortingList()
    {
        CurrencyListStore::instance()->sorting->clear();
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return Currency::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return CurrencyItem::class;
    }
}
