<?php namespace Lovata\Shopaholic\Components;

use Lang;
use Response;
use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Models\Category;

/**
 * Class CategoryPage
 * @package Lovata\Shopaholic\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class CategoryPage extends ComponentBase
{
    /**
     * @var null|Category
     */
    protected $obCategory = null;
    
    public function componentDetails()
    {
        return [
            'name' => Lang::get('lovata.shopaholic::lang.component.category_data_name'),
            'description' => Lang::get('lovata.shopaholic::lang.component.category_data_description'),
        ];
    }

    public function defineProperties()
    {
        return [
            'error_404' => [
                'title'             => Lang::get('lovata.shopaholic::lang.component.category_data_property_name_error_404'),
                'description'       => Lang::get('lovata.shopaholic::lang.component.category_data_property_description_error_404'),
                'default'           => 'on',
                'type'              => 'dropdown',
                'options'           => [
                    'on' => Lang::get('lovata.shopaholic::lang.component.property_value_on'),
                    'off' => Lang::get('lovata.shopaholic::lang.component.property_value_off'),
                ],
            ],
            'slug' => [
                'title'             => Lang::get('lovata.shopaholic::lang.component.property_slug'),
                'type'              => 'string',
                'default'           => '{{ :slug }}',
            ],
        ];
    }
    
    public function onRun()
    {

        $bDisplayError404 = $this->property('error_404') == 'on' ? true : false;

        $sCategorySlug =  $this->property('slug');
        if(empty($sCategorySlug)) {
            
            if(!$bDisplayError404) {
                return;
            }
            
            return Response::make($this->controller->run('404')->getContent(), 404);
        }

        /** @var Category $obCategory */
        $obCategory = Category::active()->slug($sCategorySlug)->first();
        if(empty($obCategory)) {

            if(!$bDisplayError404) {
                return;
            }
            
            return Response::make($this->controller->run('404')->getContent(), 404);
        }
        
        $this->obCategory = $obCategory;
        return;
    }

    /**
     * Get Category data with children
     * @return array
     */
    public function get() {
        if(!empty($this->obCategory)) {
            return Category::getCacheData($this->obCategory->id, $this->obCategory);
        }
        
        return null;
    }
}