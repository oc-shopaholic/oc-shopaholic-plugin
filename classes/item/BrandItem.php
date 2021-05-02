<?php namespace Lovata\Shopaholic\Classes\Item;

use Cms\Classes\Page as CmsPage;

use Lovata\Toolbox\Classes\Item\ElementItem;
use Lovata\Toolbox\Classes\Helper\PageHelper;

use Lovata\Shopaholic\Models\Settings;
use Lovata\Shopaholic\Models\Brand;

/**
 * Class BrandItem
 * @package Lovata\Shopaholic\Classes\Item
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property int                                                     $id
 * @property string                                                  $name
 * @property string                                                  $slug
 * @property string                                                  $code
 *
 * @property string                                                  $preview_text
 * @property \System\Models\File                                     $preview_image
 * @property \System\Models\File                                     $icon
 *
 * @property string                                                  $description
 * @property \October\Rain\Database\Collection|\System\Models\File[] $images
 */
class BrandItem extends ElementItem
{
    const MODEL_CLASS = Brand::class;

    public static $arQueryWith = ['preview_image', 'icon', 'images'];

    /** @var Brand */
    protected $obElement = null;

    /**
     * Returns URL of a brand page.
     *
     * @param string|null $sPageCode
     * @param array  $arRemoveParamList
     *
     * @return string
     */
    public function getPageUrl($sPageCode = null, $arRemoveParamList = [])
    {
        if (empty($sPageCode)) {
            $sPageCode = Settings::getValue('default_brand_page_id', 'brand');
        }

        //Get URL params
        $arParamList = $this->getPageParamList($sPageCode, $arRemoveParamList);

        //Generate page URL
        $sURL = CmsPage::url($sPageCode, $arParamList);

        return $sURL;
    }

    /**
     * Get URL param list by page code
     * @param string $sPageCode
     * @param array  $arRemoveParamList
     * @return array
     */
    public function getPageParamList($sPageCode, $arRemoveParamList = []) : array
    {
        $arResult = [];
        if (!empty($arRemoveParamList)) {
            foreach ($arRemoveParamList as $sParamName) {
                $arResult[$sParamName] = null;
            }
        }

        //Get URL params for page
        $arParamList = PageHelper::instance()->getUrlParamList($sPageCode, 'BrandPage');
        if (!empty($arParamList)) {
            $sPageParam = array_shift($arParamList);
            $arResult[$sPageParam] = $this->slug;
        }

        return $arResult;
    }
}
