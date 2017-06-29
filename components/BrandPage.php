<?php namespace Lovata\Shopaholic\Components;

use Event;
use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Toolbox\Traits\Helpers\TraitComponentNotFoundResponse;
use Lovata\Shopaholic\Models\Brand;
use Cms\Classes\ComponentBase;

/**
 * Class BrandPage
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class BrandPage extends ComponentBase
{
    use TraitComponentNotFoundResponse;

    /** @var null|Brand */
    protected $obBrand = null;

    /** @var  BrandItem */
    protected $obBrandItem;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'          => 'lovata.shopaholic::lang.component.brand_page_name',
            'description'   => 'lovata.shopaholic::lang.component.brand_page_description',
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        $arProperties = $this->getElementPageProperties();
        return $arProperties;
    }

    /**
     * @return \Illuminate\Http\Response|null
     */
    public function onRun()
    {
        //Get brand slug
        $sBrandSlug = $this->property('slug');
        if (empty($sBrandSlug)) {
            return $this->getErrorResponse();
        }

        //Get brand by slug
        /** @var Brand $obBrand */
        $obBrand = Brand::active()->getBySlug($sBrandSlug)->first();
        if (empty($obBrand)) {
            return $this->getErrorResponse();
        }

        $this->obBrand = $obBrand;

        //Get brand item
        $this->obBrandItem = BrandItem::make($this->obBrand->id, $this->obBrand);

        //Send event
        Event::fire('shopaholic.brand.open', [$obBrand]);

        return null;
    }

    /**
     * Get brand item
     * @return BrandItem
     */
    public function get()
    {
        return $this->obBrandItem;
    }
}