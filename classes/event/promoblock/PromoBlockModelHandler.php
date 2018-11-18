<?php namespace Lovata\Shopaholic\Classes\Event\PromoBlock;

use Lovata\Toolbox\Models\Settings;
use Lovata\Toolbox\Classes\Event\ModelHandler;

use Lovata\Shopaholic\Models\PromoBlock;
use Lovata\Shopaholic\Classes\Item\PromoBlockItem;
use Lovata\Shopaholic\Classes\Store\PromoBlockListStore;
use Lovata\Shopaholic\Classes\Store\ProductListStore;

/**
 * Class PromoBlockModelHandler
 * @package Lovata\Shopaholic\Classes\Event\PromoBlock
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class PromoBlockModelHandler extends ModelHandler
{
    /** @var PromoBlock */
    protected $obElement;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        parent::subscribe($obEvent);

        PromoBlock::extend(function ($obModel) {
            /** @var PromoBlock $obModel */
            $bSlugIsTranslatable = Settings::getValue('slug_is_translatable');
            if ($bSlugIsTranslatable) {
                $obModel->translatable[] = ['slug', 'index' => true];
            }
        });

        $obEvent->listen('shopaholic.promo_block.update.sorting', function () {
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

        if ($this->isFieldChanged('date_begin')) {
            PromoBlockListStore::instance()->sorting->clear(PromoBlockListStore::SORT_DATE_BEGIN_ASC);
            PromoBlockListStore::instance()->sorting->clear(PromoBlockListStore::SORT_DATE_BEGIN_DESC);
        }

        if ($this->isFieldChanged('date_end')) {
            PromoBlockListStore::instance()->sorting->clear(PromoBlockListStore::SORT_DATE_END_ASC);
            PromoBlockListStore::instance()->sorting->clear(PromoBlockListStore::SORT_DATE_END_DESC);
        }

        $this->checkFieldChanges('hidden', PromoBlockListStore::instance()->hidden);
        $this->checkFieldChanges('hidden', PromoBlockListStore::instance()->not_hidden);

        $this->checkFieldChanges('active', PromoBlockListStore::instance()->active);
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        $this->clearSortingList();

        ProductListStore::instance()->promo_block->clear($this->obElement->id);

        $this->clearCacheNotEmptyValue('hidden', PromoBlockListStore::instance()->hidden);
        $this->clearCacheEmptyValue('hidden', PromoBlockListStore::instance()->not_hidden);

        $this->clearCacheNotEmptyValue('active', PromoBlockListStore::instance()->active);
    }

    /**
     * Clear sorting list
     */
    protected function clearSortingList()
    {
        PromoBlockListStore::instance()->sorting->clear(PromoBlockListStore::SORT_DEFAULT);
        PromoBlockListStore::instance()->sorting->clear(PromoBlockListStore::SORT_DATE_BEGIN_ASC);
        PromoBlockListStore::instance()->sorting->clear(PromoBlockListStore::SORT_DATE_BEGIN_DESC);
        PromoBlockListStore::instance()->sorting->clear(PromoBlockListStore::SORT_DATE_END_ASC);
        PromoBlockListStore::instance()->sorting->clear(PromoBlockListStore::SORT_DATE_END_DESC);
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return PromoBlock::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return PromoBlockItem::class;
    }
}
