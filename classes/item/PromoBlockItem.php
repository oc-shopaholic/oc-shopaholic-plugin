<?php namespace Lovata\Shopaholic\Classes\Item;

use Cms\Classes\Page as CmsPage;
use Lovata\Toolbox\Classes\Item\ElementItem;
use Lovata\Toolbox\Classes\Helper\PageHelper;

use Lovata\Shopaholic\Models\PromoBlock;
use Lovata\Shopaholic\Classes\Collection\ProductCollection;

/**
 * Class PromoBlockItem
 * @package Lovata\PromoBlocksShopaholic\Classes\Item
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property int                                                             $id
 * @property string                                                          $name
 * @property string                                                          $slug
 * @property string                                                          $code
 * @property string                                                          $type
 * @property \October\Rain\Argon\Argon                                       $date_begin
 * @property \October\Rain\Argon\Argon                                       $date_end
 *
 * @property string                                                          $preview_text
 * @property \System\Models\File                                             $preview_image
 *
 * @property string                                                          $description
 * @property \October\Rain\Database\Collection|\System\Models\File[]         $images
 * @property ProductCollection|\Lovata\Shopaholic\Classes\Item\ProductItem[] $product
 */
class PromoBlockItem extends ElementItem
{
    const MODEL_CLASS = PromoBlock::class;

    /** @var PromoBlock */
    protected $obElement = null;

    /**
     * Returns URL of a promo block page.
     *
     * @param string $sPageCode
     *
     * @return string
     */
    public function getPageUrl($sPageCode = 'promo-block')
    {
        //Get URL params
        $arParamList = $this->getPageParamList($sPageCode);

        //Generate page URL
        $sURL = CmsPage::url($sPageCode, $arParamList);

        return $sURL;
    }

    /**
     * Get URL param list by page code
     * @param string $sPageCode
     * @return array
     */
    public function getPageParamList($sPageCode) : array
    {
        $arPageParamList = [];

        //Get URL params for page
        $arParamList = PageHelper::instance()->getUrlParamList($sPageCode, 'PromoBlockPage');
        if (!empty($arParamList)) {
            $sPageParam = array_shift($arParamList);
            $arPageParamList[$sPageParam] = $this->slug;
        }

        return $arPageParamList;
    }

    /**
     * Get product collection attribute
     * @return ProductCollection
     */
    protected function getProductAttribute() : ProductCollection
    {
        $obProductList = ProductCollection::make()->promo($this->id);

        return $obProductList;
    }
}
