<?php namespace Lovata\Shopaholic\Components;

use Event;
use Lovata\Toolbox\Classes\Component\ElementPage;

use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Classes\Item\BrandItem;

/**
 * Class BrandPage
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @link https://github.com/lovata/oc-shopaholic-plugin/wiki/BrandPage
 */
class BrandPage extends ElementPage
{
    protected $bNeedSmartURLCheck = true;

    /** @var \Lovata\Shopaholic\Models\Brand */
    protected $obElement;

    /** @var \Lovata\Shopaholic\Classes\Item\BrandItem */
    protected $obElementItem;

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

        if ($this->isSlugTranslatable()) {
            $obElement = Brand::active()->transWhere('slug', $sElementSlug)->first();
            if (!$this->checkTransSlug($obElement, $sElementSlug)) {
                $obElement = null;
            }
        } else {
            $obElement = Brand::active()->getBySlug($sElementSlug)->first();
        }
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
