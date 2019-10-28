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
     * @return array
     */
    public function defineProperties()
    {
        $arPropertyList         = parent::defineProperties();
        $arCategoryPropertyList = [
            'is_wildcard' => [
                'title'   => 'lovata.shopaholic::lang.component.is_wildcard',
                'type'    => 'checkbox',
                'default' => 0,
            ],
        ];

        $arPropertyList = array_merge($arPropertyList, $arCategoryPropertyList);

        return $arPropertyList;
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

        if (!$this->property('is_wildcard')) {
            $obElement = $this->getElementBySlug($sElementSlug);
        } else {
            $obElement = $this->getElementByWildcard($sElementSlug);
        }

        if (!empty($obElement)) {
            Event::fire('shopaholic.category.open', [$obElement]);
        }

        return $obElement;
    }

    /**
     * Get category by default
     * @param string $sElementSlug
     * @return Category|null
     */
    protected function getElementBySlug($sElementSlug)
    {
        if ($this->isSlugTranslatable()) {
            $obElement = Category::active()->transWhere('slug', $sElementSlug)->first();
            if (!$this->checkTransSlug($obElement, $sElementSlug)) {
                $obElement = null;
            }
        } else {
            $obElement = Category::active()->getBySlug($sElementSlug)->first();
        }

        return $obElement;
    }

    /**
     * Get category by wildcard
     * @param string $sElementSlug
     * @return Category|null
     */
    protected function getElementByWildcard($sElementSlug)
    {
        $arSlugList = explode('/', $sElementSlug);

        if (empty($arSlugList) || !krsort($arSlugList)) {
            return null;
        }

        $sElementSlug = array_shift($arSlugList);

        $obElement = $this->getElementBySlug($sElementSlug);

        $obNestingElement = $obElement;

        foreach ($arSlugList as $sSlug) {
            $obNestingElement = $this->getNestingElement($sSlug, $obNestingElement);

            if (empty($obNestingElement)) {
                return null;
            }
        }

        return $obElement;
    }

    /**
     * Get nesting element
     * @param string $sElementSlug
     * @param Category $obNestingElement
     * @return Category
     */
    protected function getNestingElement($sElementSlug, $obNestingElement)
    {
        $obElement = $this->getElementBySlug($sElementSlug);

        if (empty($obElement) || empty($obNestingElement) || $obElement->id != $obNestingElement->parent_id) {
            return null;
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
