<?php namespace Lovata\Shopaholic\Classes\Item;

use Lovata\Toolbox\Classes\Item\ElementItem;

use Lovata\Shopaholic\Models\Brand;

/**
 * Class BrandItem
 * @package Lovata\Shopaholic\Classes\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @see \Lovata\Shopaholic\Tests\Unit\Item\BrandItemTest
 * @link https://github.com/lovata/oc-shopaholic-plugin/wiki/BrandItem
 *
 * @property int    $id
 * @property string $name
 * @property string $slug
 * @property string $code
 *
 * @property string $preview_text
 * @property \System\Models\File $preview_image
 *
 * @property string $description
 * @property \October\Rain\Database\Collection|\System\Models\File[]  $images
 */
class BrandItem extends ElementItem
{
    const MODEL_CLASS = Brand::class;

    /** @var Brand */
    protected $obElement = null;

    /**
     * Set element object
     */
    protected function setElementObject()
    {
        $this->obElement = Brand::active()->find($this->iElementID);
    }
}
