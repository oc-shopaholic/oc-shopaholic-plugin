<?php namespace Lovata\Shopaholic\Tests\Unit\Models;

include_once __DIR__.'/../../../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../../../tests/PluginTestCase.php';

use PluginTestCase;
use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;
use Lovata\Toolbox\Traits\Tests\TestModelHasImages;
use Lovata\Toolbox\Traits\Tests\TestModelHasPreviewImage;
use Lovata\Toolbox\Traits\Tests\TestModelValidationNameField;
use Lovata\Toolbox\Traits\Tests\TestModelValidationSlugField;

/**
 * Class ProductTest
 * @package Lovata\Shopaholic\Tests\Unit\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class ProductTest extends PluginTestCase
{
    use TestModelHasPreviewImage;
    use TestModelHasImages;

    use TestModelValidationNameField;
    use TestModelValidationSlugField;
    protected $sModelClass;

    /**
     * ProductTest constructor.
     */
    public function __construct()
    {
        $this->sModelClass = Product::class;
        parent::__construct();
    }

    /**
     * Check model "offer" relation config
     */
    public function testHasOfferRelation()
    {
        $sErrorMessage = $this->sModelClass.' model has not correct "offer" relation config';

        /** @var Product $obModel */
        $obModel = new Product();
        self::assertNotEmpty($obModel->hasMany, $sErrorMessage);
        self::assertArrayHasKey('offer', $obModel->hasMany, $sErrorMessage);
        self::assertEquals([Offer::class], $obModel->hasMany['offer'], $sErrorMessage);
    }

    /**
     * Check model "category" relation config
     */
    public function testHasCategoryRelation()
    {
        $sErrorMessage = $this->sModelClass.' model has not correct "category" relation config';

        /** @var Product $obModel */
        $obModel = new Product();
        self::assertNotEmpty($obModel->belongsTo, $sErrorMessage);
        self::assertArrayHasKey('category', $obModel->belongsTo, $sErrorMessage);
        self::assertEquals([Category::class], $obModel->belongsTo['category'], $sErrorMessage);
    }

    /**
     * Check model "brand" relation config
     */
    public function testHasBrandRelation()
    {
        $sErrorMessage = $this->sModelClass.' model has not correct "brand" relation config';

        /** @var Product $obModel */
        $obModel = new Product();
        self::assertNotEmpty($obModel->belongsTo, $sErrorMessage);
        self::assertArrayHasKey('brand', $obModel->belongsTo, $sErrorMessage);
        self::assertEquals([Brand::class], $obModel->belongsTo['brand'], $sErrorMessage);
    }
}