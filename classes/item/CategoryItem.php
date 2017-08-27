<?php namespace Lovata\Shopaholic\Classes\Item;

use Kharanenka\Helper\CCache;

use Lovata\Toolbox\Classes\Item\ElementItem;

use Lovata\Shopaholic\Plugin;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Classes\Store\ProductListStore;
use Lovata\Shopaholic\Classes\Collection\ProductCollection;
use Lovata\Shopaholic\Classes\Collection\CategoryCollection;

/**
 * Class CategoryItem
 * @package Lovata\Shopaholic\Classes\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @see \Lovata\Shopaholic\Tests\Unit\Item\CategoryItemTest
 *
 * @property              $id
 * @property string       $name
 * @property string       $slug
 * @property string       $code
 * @property int          $nest_depth
 * @property int          $parent_id
 * @property int          $product_count
 *
 * @property string       $preview_text
 * @property array        $preview_image
 *
 * @property string       $description
 * @property array        $images
 *
 * @property CategoryItem $parent
 *
 * @property array                             $children_id_list
 * @property CategoryCollection|CategoryItem[] $children
 *
 * Properties for Shopaholic
 * @see \Lovata\PropertiesShopaholic\Classes\Event\CategoryModelHandler::extendCategoryItem
 *
 * @method addProductPropertyIDList()
 * @property array $product_property_list
 * @property \Lovata\PropertiesShopaholic\Classes\Collection\PropertyCollection $product_property
 *
 * @method addOfferPropertyIDList()
 * @property array $offer_property_list
 * @property \Lovata\PropertiesShopaholic\Classes\Collection\PropertyCollection $offer_property
 */
class CategoryItem extends ElementItem
{
    const CACHE_TAG_ELEMENT = 'shopaholic-category-element';

    /** @var Category */
    protected $obElement = null;

    public $arRelationList = [
        'parent' => [
            'class' => CategoryItem::class,
            'field' => 'parent_id',
        ],
        'children' => [
            'class' => CategoryCollection::class,
            'field' => 'children_id_list',
        ],
    ];

    /**
     * Set element object
     */
    protected function setElementObject()
    {
        if(!empty($this->obElement) && ! $this->obElement instanceof Category) {
            $this->obElement = null;
        }

        if(!empty($this->obElement) || empty($this->iElementID)) {
            return;
        }

        $this->obElement = Category::active()->find($this->iElementID);
    }

    /**
     * Get cache tag array for model
     * @return array
     */
    protected static function getCacheTag()
    {
        return [Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT];
    }

    /**
     * Set element data from model object
     *
     * @return array
     */
    protected function getElementData()
    {
        if(empty($this->obElement)) {
            return null;
        }

        $arResult = [
            'id'            => $this->obElement->id,
            'name'          => $this->obElement->name,
            'slug'          => $this->obElement->slug,
            'code'          => $this->obElement->code,
            'preview_text'  => $this->obElement->preview_text,
            'description'   => $this->obElement->description,
            'nest_depth'    => $this->obElement->getDepth(),
            'parent_id'     => $this->obElement->parent_id,
            'preview_image' => $this->obElement->getFileData('preview_image'),
            'images'        => $this->obElement->getFileListData('images'),
        ];

        $arResult['children_id_list'] = $this->obElement->children()
            ->active()
            ->orderBy('nest_left', 'asc')
            ->lists('id');

        return $arResult;
    }

    /**
     * Get product count for category
     * @return int
     */
    protected function getProductCountAttribute()
    {
        $iProductCount = $this->getAttribute('product_count');
        if($iProductCount !== null) {
            return $iProductCount;
        }
        
        //Get product count from cache
        $arCacheTag = [Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT, ProductListStore::CACHE_TAG_LIST];
        $sCacheKey = 'product_count_'.$this->id;

        $iProductCount = CCache::get($arCacheTag, $sCacheKey);
        if($iProductCount !== null) {
            return $iProductCount;
        }
        
        //Calculate product count from child categories
        $iProductCount = 0;
        $obChildCategoryCollect = $this->children;
        if($obChildCategoryCollect->isNotEmpty()) {
            /** @var CategoryItem $obChildCategoryItem */
            foreach($obChildCategoryCollect as $obChildCategoryItem) {
                if($obChildCategoryItem->isEmpty()) {
                    continue;
                }
                
                $iProductCount += $obChildCategoryItem->product_count;
            }
        }
        
        $obProductCollection = ProductCollection::make()->saved(self::class.'_active');
        if(empty($obProductCollection)) {
            $obProductCollection = ProductCollection::make()->active()->save(self::class.'_active');
        }
        
        $iProductCount += $obProductCollection->category($this->id)->count();
        
        CCache::forever($arCacheTag, $sCacheKey, $iProductCount);
        $this->setAttribute('product_count', $iProductCount);
        
        return $iProductCount;
    }

    /**
     * Clear product count cache
     */
    public function clearProductCount()
    {
        $arCacheTag = [Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT, ProductListStore::CACHE_TAG_LIST];
        $sCacheKey = 'product_count_'.$this->id;
        
        CCache::clear($arCacheTag, $sCacheKey);
        
        $obParentItem = $this->parent;
        if($obParentItem->isEmpty()) {
            return;
        }
        
        $obParentItem->clearProductCount();
    }
}