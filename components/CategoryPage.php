<?php namespace Lovata\Shopaholic\Components;

use Event;
use Lovata\Toolbox\Classes\Component\ElementPage;

use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Classes\Item\CategoryItem;

/**
 * Class CategoryPage
 * @package Lovata\Shopaholic\Components
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @link    https://github.com/lovata/oc-shopaholic-plugin/wiki/CategoryPage
 */
class CategoryPage extends ElementPage
{
    protected $bNeedSmartURLCheck = true;

    /** @var \Lovata\Shopaholic\Models\Category */
    protected $obElement;

    /** @var \Lovata\Shopaholic\Classes\Item\CategoryItem */
    protected $obElementItem;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'lovata.shopaholic::lang.component.category_page_name',
            'description' => 'lovata.shopaholic::lang.component.category_page_description',
        ];
    }

    /**
     * Get element object
     * @param string $sElementSlug
     * @return Category
     */
    protected function getElementObject($sElementSlug)
    {
        if (empty($sElementSlug)) {
            return null;
        }

        if ($this->isSlugTranslatable()) {
            $obElement = Category::active()->transWhere('slug', $sElementSlug)->first();
            if (!$this->checkTransSlug($obElement, $sElementSlug)) {
                $obElement = null;
            }
        } else {
            $obElement = Category::active()->getBySlug($sElementSlug)->first();
        }
        if (!empty($obElement)) {
            Event::fire('shopaholic.category.open', [$obElement]);
        }

        return $obElement;
    }

    /**
     * Make new element item
     * @param int      $iElementID
     * @param Category $obElement
     * @return CategoryItem
     */
    protected function makeItem($iElementID, $obElement)
    {
        return CategoryItem::make($iElementID, $obElement);
    }
}
