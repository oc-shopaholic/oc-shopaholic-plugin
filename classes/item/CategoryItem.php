<?php namespace Lovata\Shopaholic\Classes\Item;

use Cms\Classes\Page as CmsPage;

use Kharanenka\Helper\CCache;

use Lovata\Toolbox\Classes\Item\ElementItem;
use Lovata\Toolbox\Classes\Helper\PageHelper;

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
 * @link https://github.com/lovata/oc-shopaholic-plugin/wiki/CategoryItem
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
 * @property \System\Models\File $preview_image
 *
 * @property string       $description
 * @property \October\Rain\Database\Collection|\System\Models\File[]  $images
 *
 * @property \October\Rain\Argon\Argon $updated_at
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
 * @property \Lovata\PropertiesShopaholic\Classes\Collection\PropertyCollection|\Lovata\PropertiesShopaholic\Classes\Item\PropertyItem[] $product_property
 *
 * @method addOfferPropertyIDList()
 * @property array $offer_property_list
 * @property \Lovata\PropertiesShopaholic\Classes\Collection\PropertyCollection|\Lovata\PropertiesShopaholic\Classes\Item\PropertyItem[] $offer_property
 *
 * Filter for Shopaholic
 * @property \Lovata\FilterShopaholic\Classes\Collection\FilterPropertyCollection|\Lovata\PropertiesShopaholic\Classes\Item\PropertyItem[] $product_filter_property
 * @property \Lovata\FilterShopaholic\Classes\Collection\FilterPropertyCollection|\Lovata\PropertiesShopaholic\Classes\Item\PropertyItem[] $offer_filter_property
 */
class CategoryItem extends ElementItem
{
    const CACHE_TAG_ELEMENT = 'shopaholic-category-element';

    /** @var Category */
    protected $obElement = null;

    public $arRelationList = [
        'parent'   => [
            'class' => CategoryItem::class,
            'field' => 'parent_id',
        ],
        'children' => [
            'class' => CategoryCollection::class,
            'field' => 'children_id_list',
        ],
    ];

    /**
     * Clear product count cache
     */
    public function clearProductCount()
    {
        $arCacheTag = [Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT, ProductListStore::CACHE_TAG_LIST];
        $sCacheKey = 'product_count_'.$this->id;

        CCache::clear($arCacheTag, $sCacheKey);

        $obParentItem = $this->parent;
        if ($obParentItem->isEmpty()) {
            return;
        }

        $obParentItem->clearProductCount();
    }

    /**
     * Returns URL of a category page.
     *
     * @param string $sPageCode
     *
     * @return string
     */
    public function getPageUrl($sPageCode)
    {
        //Get URL params for page
        $arParamList = PageHelper::instance()->getUrlParamList($sPageCode, 'CategoryPage');
        if (empty($arParamList)) {
            //Generate page URL
            $sURL = CmsPage::url($sPageCode, ['slug' => $this->slug]);

            return $sURL;
        }

        //Prepare page property list
        $arPagePropertyList = [];
        $obCategoryItem = $this;

        foreach ($arParamList as $sParamName) {
            $arPagePropertyList[$sParamName] = $obCategoryItem->slug;
            $obCategoryItem = $obCategoryItem->parent;
        }

        //Generate page URL
        $sURL = CmsPage::url($sPageCode, $arPagePropertyList);

        return $sURL;
    }

    /**
     * Set element object
     */
    protected function setElementObject()
    {
        if (!empty($this->obElement) && !$this->obElement instanceof Category) {
            $this->obElement = null;
        }

        if (!empty($this->obElement) || empty($this->iElementID)) {
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
        if (empty($this->obElement)) {
            return null;
        }

        $arResult = [
            'nest_depth'    => $this->obElement->getDepth(),
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
        if ($iProductCount !== null) {
            return $iProductCount;
        }

        //Get product count from cache
        $arCacheTag = [Plugin::CACHE_TAG, self::CACHE_TAG_ELEMENT, ProductListStore::CACHE_TAG_LIST];
        $sCacheKey = 'product_count_'.$this->id;

        $iProductCount = CCache::get($arCacheTag, $sCacheKey);
        if ($iProductCount !== null) {
            return $iProductCount;
        }

        //Calculate product count from child categories
        $iProductCount = 0;
        $obChildCategoryCollect = $this->children;
        if ($obChildCategoryCollect->isNotEmpty()) {
            /** @var CategoryItem $obChildCategoryItem */
            foreach ($obChildCategoryCollect as $obChildCategoryItem) {
                if ($obChildCategoryItem->isEmpty()) {
                    continue;
                }

                $iProductCount += $obChildCategoryItem->product_count;
            }
        }

        $obProductCollection = ProductCollection::make()->saved(self::class.'_active');
        if (empty($obProductCollection)) {
            $obProductCollection = ProductCollection::make()->active()->save(self::class.'_active');
        }

        $iProductCount += $obProductCollection->category($this->id)->count();

        CCache::forever($arCacheTag, $sCacheKey, $iProductCount);
        $this->setAttribute('product_count', $iProductCount);

        return $iProductCount;
    }
}
