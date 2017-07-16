<?php namespace Lovata\Shopaholic\Classes\Collection;

use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Toolbox\Classes\Collection\ElementCollection;

/**
 * Class BrandCollection
 * @package Lovata\Shopaholic\Classes\Collection
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class BrandCollection extends ElementCollection
{
    /**
     * Make element item
     * @param int   $iElementID
     * @param \Lovata\Shopaholic\Models\Brand  $obElement
     *
     * @return BrandItem
     */
    protected function makeItem($iElementID, $obElement = null)
    {
        return BrandItem::make($iElementID, $obElement);
    }
}