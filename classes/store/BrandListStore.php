<?php namespace Lovata\Shopaholic\Classes\Store;

use Lovata\Toolbox\Classes\Store\AbstractListStore;

use Lovata\Shopaholic\Classes\Store\Brand\ActiveListStore;
use Lovata\Shopaholic\Classes\Store\Brand\SortingListStore;
use Lovata\Shopaholic\Classes\Store\Brand\ListByCategoryStore;
use Lovata\Shopaholic\Classes\Store\Brand\ListBySiteStore;

/**
 * Class BrandListStore
 * @package Lovata\Shopaholic\Classes\Store
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * @property ActiveListStore     $active
 * @property SortingListStore    $sorting
 * @property ListByCategoryStore $category
 * @property ListBySiteStore       $site
 */
class BrandListStore extends AbstractListStore
{
    protected static $instance;

    /**
     * Init store method
     */
    protected function init()
    {
        $this->addToStoreList('sorting', SortingListStore::class);
        $this->addToStoreList('category', ListByCategoryStore::class);
        $this->addToStoreList('active', ActiveListStore::class);
        $this->addToStoreList('site', ListBySiteStore::class);
    }
}
