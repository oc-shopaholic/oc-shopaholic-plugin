<?php namespace Lovata\Shopaholic\Updates;

use App;
use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Controllers\Categories;
use Lovata\Shopaholic\Controllers\Products;
use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Product;
use October\Rain\Database\Updates\Migration;
use System\Classes\PluginManager;

/**
 * Class InsertSeo
 * @package Lovata\Shopaholic\Updates
 */
class InsertSeo extends Migration
{
    /** @var \Lovata\SeoToolbox\Classes\Helper\SeoService $obSeoService */
    protected $obSeoService;

    /** @var array $arExtend */
    protected $arExtend = [
        Category::class => [
            'controller' => Categories::class,
            'item'       => CategoryItem::class,
            'trans'      => 'lovata.shopaholic::lang.category.name',
        ],
        Product::class => [
            'controller' => Products::class,
            'item'       => ProductItem::class,
            'trans'      => 'lovata.shopaholic::lang.category.name',
        ],
        Brand::class => [
            'controller' => Brand::class,
            'item'       => BrandItem::class,
            'trans'      => 'lovata.shopaholic::lang.brand.name',
        ],
    ];

    public function __construct()
    {
        if(PluginManager::instance()->hasPlugin('Lovata.SeoToolbox')) {
            $this->obSeoService = App::make(\Lovata\SeoToolbox\Classes\Helper\SeoService::class);
        }
    }

    public function up()
    {
        if(PluginManager::instance()->hasPlugin('Lovata.SeoToolbox')) {
            $this->obSeoService->up($this->arExtend);
        }
    }

    public function down()
    {
        if(PluginManager::instance()->hasPlugin('Lovata.SeoToolbox')) {
            $this->obSeoService->down($this->arExtend);
        }
    }
}