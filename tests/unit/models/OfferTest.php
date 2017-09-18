<?php namespace Lovata\Shopaholic\Tests\Unit\Models;

include_once __DIR__.'/../../../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../../../tests/PluginTestCase.php';

use PluginTestCase;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;
use Lovata\Toolbox\Traits\Tests\TestModelHasImages;
use Lovata\Toolbox\Traits\Tests\TestModelHasPreviewImage;
use Lovata\Toolbox\Traits\Tests\TestModelValidationNameField;

/**
 * Class OfferTest
 * @package Lovata\Shopaholic\Tests\Unit\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class OfferTest extends PluginTestCase
{
    use TestModelHasPreviewImage;
    use TestModelHasImages;
    
    use TestModelValidationNameField;

    protected $sModelClass;

    /**
     * OfferTest constructor.
     */
    public function __construct()
    {
        $this->sModelClass = Offer::class;
        parent::__construct();
    }

    /**
     * Check model "product" relation config
     */
    public function testHasProductRelation()
    {
        $sErrorMessage = $this->sModelClass.' model has not correct "product" relation config';

        /** @var Offer $obModel */
        $obModel = new Offer();
        self::assertNotEmpty($obModel->belongsTo, $sErrorMessage);
        self::assertArrayHasKey('product', $obModel->belongsTo, $sErrorMessage);
        self::assertEquals([Product::class], $obModel->belongsTo['product'], $sErrorMessage);
    }
}