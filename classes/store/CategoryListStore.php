<?php namespace Lovata\Shopaholic\Classes\Store;

use Lovata\Toolbox\Classes\Store\AbstractListStore;

use Lovata\Shopaholic\Classes\Store\Category\TopLevelListStore;
use Lovata\Shopaholic\Classes\Store\Category\ActiveListStore;

/**
 * Class CategoryListStore
 * @package Lovata\Shopaholic\Classes\Store
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property TopLevelListStore $top_level
 * @property ActiveListStore     $active
 */
class CategoryListStore extends AbstractListStore
{
    protected static $instance;

    /**
     * Init store method
     */
    protected function init()
    {
        $this->addToStoreList('top_level', TopLevelListStore::class);
        $this->addToStoreList('active', ActiveListStore::class);
    }
}
