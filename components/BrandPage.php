<?php namespace Lovata\Shopaholic\Components;

use Event;
use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Toolbox\Classes\Component\ElementPage;
use Lovata\Shopaholic\Models\Brand;

/**
 * Class BrandPage
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @link https://github.com/lovata/oc-shopaholic-plugin/wiki/BrandPage
 */
class BrandPage extends ElementPage
{
    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'lovata.shopaholic::lang.component.brand_page_name',
            'description' => 'lovata.shopaholic::lang.component.brand_page_description',
        ];
    }

    /**
     * Get element object
     * @param string $sElementSlug
     * @return Brand
     */
    protected function getElementObject($sElementSlug)
    {
        if (empty($sElementSlug)) {
            return null;
        }

        $obElement = Brand::active()->getBySlug($sElementSlug)->first();
        if (!empty($obElement)) {
            Event::fire('shopaholic.brand.open', [$obElement]);
        }

        return $obElement;
    }

    /**
     * Make new element item
     * @param int   $iElementID
     * @param Brand $obElement
     * @return BrandItem
     */
    protected function makeItem($iElementID, $obElement)
    {
        return BrandItem::make($iElementID, $obElement);
    }
}
