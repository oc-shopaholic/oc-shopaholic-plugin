<?php namespace Lovata\Shopaholic\Tests\Unit\Models;

include_once __DIR__.'/../../../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../../../tests/PluginTestCase.php';

use PluginTestCase;
use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Tests\Fixtures\ImageSetsTestTrait;
use Lovata\Toolbox\Models\ImageSet;
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

    use ImageSetsTestTrait;

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
     * Set up test method
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpImageSets();
    }

    /**
     * Tear down test method
     */
    public function tearDown(): void
    {
        $this->tearDownImageSets();
        parent::tearDown();
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

    /**
     * Check model "image_sets" relation config
     */
    public function testHasImageSetsRelation()
    {
        $sErrorMessage = $this->sModelClass.' model has not correct "image_sets" relation config';

        /** @var Product $obModel */
        $obModel = new Product();
        self::assertArrayHasKey('image_sets', $obModel->morphMany, $sErrorMessage);
        self::assertEquals([ImageSet::class, 'name' => 'imageable', 'delete' => true], $obModel->morphMany['image_sets'], $sErrorMessage);
        self::assertContains('image_sets', $obModel->cached, $sErrorMessage);
    }

    /**
     * Check that "image_sets" relation works
     */
    public function testImageSetsRelation()
    {
        $obProduct = $this->createProduct();
        $obImageSet = $this->createImageSet($obProduct, $this->createProfile('Tablet', 768), ['first.jpg', 'second.jpg']);

        $obProduct = Product::find($obProduct->id);
        self::assertCount(1, $obProduct->image_sets);
        self::assertEquals($obImageSet->id, $obProduct->image_sets->first()->id);
        self::assertEquals(['first.jpg', 'second.jpg'], $obProduct->image_sets->first()->images->pluck('file_name')->all());

        $obImageSet = ImageSet::find($obImageSet->id);
        self::assertInstanceOf(Product::class, $obImageSet->imageable);
        self::assertEquals($obProduct->id, $obImageSet->imageable->id);
    }

    /**
     * Image set created before product is saved, is attached to product with deferred binding
     */
    public function testImageSetsDeferredBinding()
    {
        $sSessionKey = uniqid('session_key', true);

        $obImageSet = new ImageSet();
        $obImageSet->profile_id = $this->createProfile('Tablet', 768)->id;
        $obImageSet->save();
        $obImageSet->images()->add($this->createFile('first.jpg'));

        $obProduct = new Product();
        $obProduct->name = 'Product';
        $obProduct->slug = 'product';
        $obProduct->image_sets()->add($obImageSet, $sSessionKey);
        $obProduct->save(null, $sSessionKey);

        $obImageSet = ImageSet::find($obImageSet->id);
        self::assertEquals(Product::class, $obImageSet->imageable_type);
        self::assertEquals($obProduct->id, $obImageSet->imageable_id);
        self::assertCount(1, $obImageSet->images);
    }

    /**
     * Soft delete keeps image sets and files, restore returns them
     */
    public function testSoftDeleteKeepsImageSets()
    {
        $obProduct = $this->createProduct();
        $obImageSet = $this->createImageSet($obProduct, $this->createProfile('Tablet', 768), ['first.jpg', 'second.jpg']);
        $arFileIDList = $obImageSet->images->pluck('id')->all();
        $arFilePathList = $this->getFilePathList($obImageSet);

        $obProduct->delete();
        self::assertNull(Product::find($obProduct->id));
        $this->assertImageSetExists($obImageSet, $arFileIDList, $arFilePathList);

        Product::withTrashed()->find($obProduct->id)->restore();
        $obProduct = Product::find($obProduct->id);
        self::assertNotNull($obProduct);
        self::assertCount(1, $obProduct->image_sets);
        self::assertCount(2, $obProduct->image_sets->first()->images);
        $this->assertImageSetExists($obImageSet, $arFileIDList, $arFilePathList);
    }

    /**
     * Permanent delete removes image sets and files: directly and from trash
     */
    public function testForceDeleteRemovesImageSets()
    {
        $obProfile = $this->createProfile('Tablet', 768);
        $obProduct = $this->createProduct();
        $obTrashedProduct = $this->createProduct('Trashed');
        $obOtherProduct = $this->createProduct('Other');

        $obImageSet = $this->createImageSet($obProduct, $obProfile, ['first.jpg', 'second.jpg']);
        $obTrashedImageSet = $this->createImageSet($obTrashedProduct, $obProfile, ['third.jpg']);
        $obOtherImageSet = $this->createImageSet($obOtherProduct, $obProfile);

        $arFileIDList = $obImageSet->images->pluck('id')->all();
        $arFilePathList = $this->getFilePathList($obImageSet);
        $obProduct->forceDelete();
        $this->assertImageSetRemoved($obImageSet, $arFileIDList, $arFilePathList);

        $arFileIDList = $obTrashedImageSet->images->pluck('id')->all();
        $arFilePathList = $this->getFilePathList($obTrashedImageSet);
        $obTrashedProduct->delete();
        Product::withTrashed()->find($obTrashedProduct->id)->forceDelete();
        $this->assertImageSetRemoved($obTrashedImageSet, $arFileIDList, $arFilePathList);

        self::assertNotNull(ImageSet::find($obOtherImageSet->id));
        self::assertCount(1, ImageSet::find($obOtherImageSet->id)->images);
    }

    /**
     * Create product
     * @param string $sName
     * @return Product
     */
    protected function createProduct($sName = 'Product')
    {
        return Product::create([
            'name' => $sName,
            'slug' => str_slug($sName),
        ]);
    }
}
