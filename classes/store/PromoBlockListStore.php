<?php namespace Lovata\Shopaholic\Classes\Store;

use Lovata\Toolbox\Classes\Store\AbstractListStore;

use Lovata\Shopaholic\Classes\Store\PromoBlock\ActiveListStore;
use Lovata\Shopaholic\Classes\Store\PromoBlock\SortingListStore;
use Lovata\Shopaholic\Classes\Store\PromoBlock\HiddenListStore;
use Lovata\Shopaholic\Classes\Store\PromoBlock\NotHiddenListStore;

/**
 * Class PromoBlockListStore
 * @package Lovata\Shopaholic\Classes\Store
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * @property ActiveListStore    $active
 * @property SortingListStore   $sorting
 * @property HiddenListStore    $hidden
 * @property NotHiddenListStore $not_hidden
 */
class PromoBlockListStore extends AbstractListStore
{
    protected static $instance;

    const SORT_DEFAULT = 'default';
    const SORT_DATE_BEGIN_ASC = 'date_begin|asc';
    const SORT_DATE_BEGIN_DESC = 'date_begin|desc';
    const SORT_DATE_END_ASC = 'date_end|asc';
    const SORT_DATE_END_DESC = 'date_end|desc';

    /**
     * Init store method
     */
    protected function init()
    {
        $this->addToStoreList('active', ActiveListStore::class);
        $this->addToStoreList('sorting', SortingListStore::class);
        $this->addToStoreList('hidden', HiddenListStore::class);
        $this->addToStoreList('not_hidden', NotHiddenListStore::class);
    }
}
