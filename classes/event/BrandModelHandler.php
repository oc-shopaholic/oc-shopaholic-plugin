<?php namespace Lovata\Shopaholic\Classes\Event;

use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Classes\Store\BrandListStore;
use Lovata\Shopaholic\Models\Brand;
use Lovata\Toolbox\Classes\Event\ModelHandler;

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