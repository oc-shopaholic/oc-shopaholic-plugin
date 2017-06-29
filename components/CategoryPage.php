<?php namespace Lovata\Shopaholic\Components;

use Event;
use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Toolbox\Traits\Helpers\TraitComponentNotFoundResponse;

/**
 * Class CategoryPage
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CategoryPage extends ComponentBase
{
    use TraitComponentNotFoundResponse;

    /** @var null|Category */
    protected $obCategory = null;

    /** @var  CategoryItem */
    protected $obCategoryItem;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'          => 'lovata.shopaholic::lang.component.category_page_name',
            'description'   => 'lovata.shopaholic::lang.component.category_page_description',
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
        $sCategorySlug =  $this->property('slug');
        if(empty($sCategorySlug)) {
            return $this->getErrorResponse();
        }

        /** @var Category $obCategory */
        $obCategory = Category::active()->getBySlug($sCategorySlug)->first();
        if(empty($obCategory)) {
            return $this->getErrorResponse();
        }
        
        $this->obCategory = $obCategory;

        //Get category item
        $this->obCategoryItem = CategoryItem::make($obCategory->id, $obCategory);

        //Send event
        Event::fire('shopaholic.category.open', [$obCategory]);

        return null;
    }

    /**
     * Get category item
     * @return CategoryItem
     */
    public function get()
    {
        return $this->obCategoryItem;
    }
}