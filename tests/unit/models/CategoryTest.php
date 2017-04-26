<?php namespace Lovata\Shopaholic\Tests\Unit\Models;

include_once __DIR__.'/../../../../toolbox/vendor/autoload.php';

use Lovata\Shopaholic\Models\Category;
use Lovata\Toolbox\Traits\Tests\TestModelCacheTagConst;
use Lovata\Toolbox\Traits\Tests\TestModelGetDataMethod;
use Lovata\Toolbox\Traits\Tests\TestModelHasImages;
use Lovata\Toolbox\Traits\Tests\TestModelHasPreviewImage;
use Lovata\Toolbox\Traits\Tests\TestModelValidationNameField;
use Lovata\Toolbox\Traits\Tests\TestModelValidationSlugField;
use PluginTestCase;

/**
 * Class CategoryTest
 * @package Lovata\Shopaholic\Tests\Unit\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class CategoryTest extends PluginTestCase
{
    use TestModelCacheTagConst;
    use TestModelHasPreviewImage;
    use TestModelHasImages;
    use TestModelValidationNameField;
    use TestModelValidationSlugField;
    use TestModelGetDataMethod;

    const MODEL_NAME = '\Lovata\Shopaholic\Models\Category';

    protected $arModelData = [
        'name'         => 'Mobile phone',
        'slug'         => 'mobile_phone',
        'active'       => true,
        'code'         => 'mobile_phone',
        'preview_text' => 'test preview text',
        'description'  => '<p>test description</p>',
    ];

    /**
     * Check model "product" relation config
     */
    public function testHasProductRelation()
    {
        $sModelClass = self::MODEL_NAME;
        $sErrorMessage = $sModelClass.' model has not correct "product" relation config';

        /** @var Category $obModel */
        $obModel = new Category();
        self::assertNotEmpty($obModel->hasMany, $sErrorMessage);
        self::assertArrayHasKey('products', $obModel->hasMany, $sErrorMessage);
        self::assertEquals($obModel->hasMany['products'], ['Lovata\Shopaholic\Models\Product'], $sErrorMessage);
    }
}