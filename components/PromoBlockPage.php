<?php namespace Lovata\Shopaholic\Components;

use Event;
use Lovata\Toolbox\Classes\Component\ElementPage;

use Lovata\Shopaholic\Models\PromoBlock;
use Lovata\Shopaholic\Classes\Item\PromoBlockItem;

/**
 * Class PromoBlockPage
 * @package Lovata\Shopaholic\Components
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @link    https://github.com/lovata/oc-shopaholic-plugin/wiki/PromoBlockPage
 */
class PromoBlockPage extends ElementPage
{
    protected $bNeedSmartURLCheck = true;

    /** @var \Lovata\Shopaholic\Models\PromoBlock */
    protected $obElement;

    /** @var \Lovata\Shopaholic\Classes\Item\PromoBlockItem */
    protected $obElementItem;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'lovata.shopaholic::lang.component.promo_block_page_name',
            'description' => 'lovata.shopaholic::lang.component.promo_block_page_description',
        ];
    }

    /**
     * Get element object
     * @param string $sElementSlug
     * @return PromoBlock
     */
    protected function getElementObject($sElementSlug)
    {
        if (empty($sElementSlug)) {
            return null;
        }

        if ($this->isSlugTranslatable()) {
            $obElement = PromoBlock::active()->transWhere('slug', $sElementSlug)->first();
            if (!$this->checkTransSlug($obElement, $sElementSlug)) {
                $obElement = null;
            }
        } else {
            $obElement = PromoBlock::active()->getBySlug($sElementSlug)->first();
        }

        if (!empty($obElement)) {
            Event::fire('shopaholic.promo_block.open', [$obElement]);
        }

        return $obElement;
    }

    /**
     * Make new element item
     * @param int        $iElementID
     * @param PromoBlock $obElement
     * @return PromoBlockItem
     */
    protected function makeItem($iElementID, $obElement)
    {
        return PromoBlockItem::make($iElementID, $obElement);
    }
}
