<?php namespace Lovata\Shopaholic\Tests\Unit\Models;

include_once __DIR__.'/../../../../toolbox/vendor/autoload.php';

use Lovata\Shopaholic\Models\Offer;
use Lovata\Toolbox\Traits\Tests\TestModelCacheTagConst;
use Lovata\Toolbox\Traits\Tests\TestModelGetDataMethod;
use Lovata\Toolbox\Traits\Tests\TestModelHasImages;
use Lovata\Toolbox\Traits\Tests\TestModelHasPreviewImage;
use Lovata\Toolbox\Traits\Tests\TestModelValidationNameField;
use Lovata\Toolbox\Traits\Tests\TestModelValidationSlugField;
use PluginTestCase;

/**
 * Class OfferTest
 * @package Lovata\Shopaholic\Tests\Unit\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class OfferTest extends PluginTestCase
{
    use TestModelCacheTagConst;
    use TestModelHasPreviewImage;
    use TestModelHasImages;
    use TestModelValidationNameField;
    use TestModelGetDataMethod;

    const MODEL_NAME = '\Lovata\Shopaholic\Models\Offer';

    /** @var array Used in TestModelGetDataMethod */
    protected $arCreateModelData = [
        'name'         => 'Mobile phone',
        'active'       => true,
        'code'         => 'mobile_phone',
        'preview_text' => 'test preview text',
        'description'  => '<p>test description</p>',
        'price'        => '1 200.58',
        'old_price'    => '1 400.58',
        'quantity'     => 10,
        'product_id'   => 1,
    ];

    /** @var array Used in TestModelGetDataMethod */
    protected $arModelData = [
        'id'              => 1,
        'name'            => 'Mobile phone',
        'code'            => 'mobile_phone',
        'preview_text'    => 'test preview text',
        'description'     => '<p>test description</p>',
        'preview_image'   => null,
        'images'          => [],
        'price'           => '1 200.58',
        'price_value'     => '1200.58',
        'old_price'       => '1 400.58',
        'old_price_value' => '1400.58',
        'quantity'        => 10,
        'product_id'      => 1,
    ];

    /**
     * Check model "product" relation config
     */
    public function testHasProductRelation()
    {
        $sModelClass = self::MODEL_NAME;
        $sErrorMessage = $sModelClass.' model has not correct "product" relation config';

        /** @var Offer $obModel */
        $obModel = new Offer();
        self::assertNotEmpty($obModel->belongsTo, $sErrorMessage);
        self::assertArrayHasKey('product', $obModel->belongsTo, $sErrorMessage);
        self::assertEquals($obModel->belongsTo['product'], ['Lovata\Shopaholic\Models\Product'], $sErrorMessage);
    }
}