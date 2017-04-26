<?php namespace Lovata\Shopaholic\Tests\Unit\Models;

include_once __DIR__.'/../../../../toolbox/vendor/autoload.php';

use Lovata\Shopaholic\Models\Product;
use Lovata\Toolbox\Traits\Tests\TestModelCacheTagConst;
use Lovata\Toolbox\Traits\Tests\TestModelGetDataMethod;
use Lovata\Toolbox\Traits\Tests\TestModelHasImages;
use Lovata\Toolbox\Traits\Tests\TestModelHasPreviewImage;
use Lovata\Toolbox\Traits\Tests\TestModelValidationNameField;
use Lovata\Toolbox\Traits\Tests\TestModelValidationSlugField;
use PluginTestCase;

/**
 * Class ProductTest
 * @package Lovata\Shopaholic\Tests\Unit\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class ProductTest extends PluginTestCase
{
    use TestModelCacheTagConst;
    use TestModelHasPreviewImage;
    use TestModelHasImages;
    use TestModelValidationNameField;
    use TestModelValidationSlugField;
    use TestModelGetDataMethod;

    const MODEL_NAME = '\Lovata\Shopaholic\Models\Product';

    protected $arModelData = [
        'name'         => 'Windows phone',
        'slug'         => 'windows_phone',
        'active'       => true,
        'code'         => 'phone_code',
        'category_id'  => 1,
        'brand_id'     => 1,
        'preview_text' => 'test preview text',
        'description'  => '<p>test description</p>',
    ];

    /**
     * Check model "offer" relation config
     */
    public function testHasOfferRelation()
    {
        $sModelClass = self::MODEL_NAME;
        $sErrorMessage = $sModelClass.' model has not correct "offer" relation config';

        /** @var Product $obModel */
        $obModel = new Product();
        self::assertNotEmpty($obModel->hasMany, $sErrorMessage);
        self::assertArrayHasKey('offers', $obModel->hasMany, $sErrorMessage);
        self::assertEquals($obModel->hasMany['offers'], ['Lovata\Shopaholic\Models\Offer'], $sErrorMessage);
    }

    /**
     * Check model "category" relation config
     */
    public function testHasCategoryRelation()
    {
        $sModelClass = self::MODEL_NAME;
        $sErrorMessage = $sModelClass.' model has not correct "category" relation config';

        /** @var Product $obModel */
        $obModel = new Product();
        self::assertNotEmpty($obModel->belongsTo, $sErrorMessage);
        self::assertArrayHasKey('category', $obModel->belongsTo, $sErrorMessage);
        self::assertEquals($obModel->belongsTo['category'], ['Lovata\Shopaholic\Models\Category'], $sErrorMessage);
    }

    /**
     * Check model "brand" relation config
     */
    public function testHasBrandRelation()
    {
        $sModelClass = self::MODEL_NAME;
        $sErrorMessage = $sModelClass.' model has not correct "brand" relation config';

        /** @var Product $obModel */
        $obModel = new Product();
        self::assertNotEmpty($obModel->belongsTo, $sErrorMessage);
        self::assertArrayHasKey('brand', $obModel->belongsTo, $sErrorMessage);
        self::assertEquals($obModel->belongsTo['brand'], ['Lovata\Shopaholic\Models\Brand'], $sErrorMessage);
    }
}