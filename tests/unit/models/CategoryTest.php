<?php namespace Lovata\Shopaholic\Tests\Unit\Models;

include_once __DIR__.'/../../../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../../../tests/PluginTestCase.php';

use Lovata\Shopaholic\Models\Category;
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
    use TestModelHasPreviewImage;
    use TestModelHasImages;

    use TestModelValidationNameField;
    use TestModelValidationSlugField;

    protected $sModelClass;

    /**
     * CategoryTest constructor.
     */
    public function __construct()
    {
        $this->sModelClass = Category::class;
    }

    /**
     * Check model "product" relation config
     */
    public function testHasProductRelation()
    {
        $sErrorMessage = $this->sModelClass.' model has not correct "product" relation config';

        /** @var Category $obModel */
        $obModel = new Category();
        self::assertNotEmpty($obModel->hasMany, $sErrorMessage);
        self::assertArrayHasKey('products', $obModel->hasMany, $sErrorMessage);
        self::assertEquals('Lovata\Shopaholic\Models\Product', $obModel->hasMany['products'], $sErrorMessage);
    }
}