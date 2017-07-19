<?php namespace Lovata\Shopaholic\Classes\Event;

use Kharanenka\Helper\CCache;
use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Classes\Store\BrandListStore;
use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Plugin;

/**
 * Class BrandModelHandler
 * @package Lovata\Shopaholic\Classes\Event
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class BrandModelHandler
{
    /** @var  Brand */
    protected $obElement;

    /** @var  BrandListStore */
    protected $obBrandListStore;

    /**
     * BrandModelHandler constructor.
     *
     * @param BrandListStore $obBrandListStore
     */
    public function __construct(BrandListStore $obBrandListStore)
    {
        $this->obBrandListStore = $obBrandListStore;
    }
    
    /**
     * Add listeners
     */
    public function subscribe()
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
        
        $this->checkActiveField();
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
        
        $this->removeFromActiveList();
    }

    /**
     * Clear item cache
     */
    protected function clearItemCache()
    {
        BrandItem::clearCache($this->obElement->id);
    }

    /**
     * Check brand "active" field, if it was changed, then clear cache
     */
    private function checkActiveField()
    {
        //check product "active" field
        if($this->obElement->getOriginal('active') == $this->obElement->active) {
            return;
        }

        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, BrandListStore::CACHE_TAG_LIST];
        $sCacheKey = BrandListStore::CACHE_TAG_LIST;

        //Clear cache data
        CCache::clear($arCacheTags, $sCacheKey);
        $this->obBrandListStore->getActiveList();
    }

    /**
     * Remove brand from active product ID list
     */
    private function removeFromActiveList()
    {
        //Get cache data
        $arCacheTags = [Plugin::CACHE_TAG, BrandListStore::CACHE_TAG_LIST];
        $sCacheKey = BrandListStore::CACHE_TAG_LIST;

        //Check cache array
        $arBrandIDList = CCache::get($arCacheTags, $sCacheKey);
        if(empty($arBrandIDList)) {
            $this->obBrandListStore->getActiveList();
            return;
        }

        if(!in_array($this->obElement->id, $arBrandIDList)) {
            return;
        }

        //Remove element from cache array and save
        $iPosition = array_search($this->obElement->id, $arBrandIDList);
        if($iPosition === false) {
            return;
        }

        unset($arBrandIDList[$iPosition]);

        //Set cache data
        CCache::forever($arCacheTags, $sCacheKey, $arBrandIDList);
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