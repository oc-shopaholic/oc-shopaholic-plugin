<?php namespace Lovata\Shopaholic\Classes\Event;

use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Models\Brand;

/**
 * Class BrandModelHandler
 * @package Lovata\Shopaholic\Classes\Event
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class BrandModelHandler
{
    /** @var  Brand */
    protected $obElement;

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        Brand::extend(function ($obElement) {
            /** @var Brand $obElement */
            $obElement->bindEvent('model.afterSave', function () use($obElement) {
                $this->afterSave($obElement);
            });
        });

        Brand::extend(function ($obElement) {
            /** @var Brand $obElement */
            $obElement->bindEvent('model.afterDelete', function () use($obElement) {
                $this->afterDelete($obElement);
            });
        });
    }

    /**
     * After save event handler
     * @param Brand $obElement
     */
    public function afterSave($obElement)
    {
        if(empty($obElement) || !$obElement instanceof Brand) {
            return;
        }

        $this->obElement = $obElement;
        $this->clearItemCache();
    }

    /**
     * After delete event handler
     * @param Brand $obElement
     */
    public function afterDelete($obElement)
    {
        if(empty($obElement) || !$obElement instanceof Brand) {
            return;
        }
        
        $this->obElement = $obElement;
        $this->clearItemCache();
    }

    /**
     * Clear item cache
     */
    protected function clearItemCache()
    {
        BrandItem::clearCache($this->obElement->id);
    }
} 