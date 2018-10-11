<?php namespace Lovata\Shopaholic\Classes\Store;

use Lovata\Toolbox\Classes\Store\AbstractListStore;

use Lovata\Shopaholic\Classes\Store\Product\ActiveListStore;
use Lovata\Shopaholic\Classes\Store\Product\ListByCategoryStore;
use Lovata\Shopaholic\Classes\Store\Product\ListByBrandStore;
use Lovata\Shopaholic\Classes\Store\Product\ListByPromoBlockStore;
use Lovata\Shopaholic\Classes\Store\Product\SortingListStore;

/**
 * Class ProductListStore
 * @package Lovata\Shopaholic\Classes\Store
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property ActiveListStore       $active
 * @property ListByCategoryStore   $category
 * @property ListByBrandStore      $brand
 * @property SortingListStore      $sorting
 * @property ListByPromoBlockStore $promo_block
 */
class ProductListStore extends AbstractListStore
{
    const SORT_NO = 'no';
    const SORT_PRICE_ASC = 'price|asc';
    const SORT_PRICE_DESC = 'price|desc';
    const SORT_NEW = 'new';
    const SORT_POPULARITY_DESC = 'popularity|desc';
    const SORT_RATING_DESC = 'rating|desc';
    const SORT_RATING_ASC = 'rating|asc';

    protected static $instance;

    /**
     * Init store method
     */
    protected function init()
    {
        $this->addToStoreList('sorting', SortingListStore::class);
        $this->addToStoreList('category', ListByCategoryStore::class);
        $this->addToStoreList('brand', ListByBrandStore::class);
        $this->addToStoreList('active', ActiveListStore::class);
        $this->addToStoreList('promo_block', ListByPromoBlockStore::class);
    }
}
