<?php namespace Lovata\Shopaholic\Tests\Unit\Models;

include_once __DIR__.'/../../../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../../../tests/PluginTestCase.php';

use PluginTestCase;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Price;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Tests\Fixtures\ImageSetsTestTrait;
use Lovata\Toolbox\Models\ImageSet;
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

    use ImageSetsTestTrait;

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

    /**
     * Check model "image_sets" relation config, "price_link" relation is kept
     */
    public function testHasImageSetsRelation()
    {
        $sErrorMessage = $this->sModelClass.' model has not correct "image_sets" relation config';

        /** @var Offer $obModel */
        $obModel = new Offer();
        self::assertArrayHasKey('image_sets', $obModel->morphMany, $sErrorMessage);
        self::assertEquals([ImageSet::class, 'name' => 'imageable', 'delete' => true], $obModel->morphMany['image_sets'], $sErrorMessage);
        self::assertContains('image_sets', $obModel->cached, $sErrorMessage);

        self::assertArrayHasKey('price_link', $obModel->morphMany, $sErrorMessage);
        self::assertEquals(Price::class, $obModel->morphMany['price_link'][0], $sErrorMessage);
    }

    /**
     * Check that "image_sets" relation works
     */
    public function testImageSetsRelation()
    {
        $obOffer = $this->createOffer();
        $obImageSet = $this->createImageSet($obOffer, $this->createProfile('Tablet', 768), ['first.jpg', 'second.jpg']);

        $obOffer = Offer::find($obOffer->id);
        self::assertCount(1, $obOffer->image_sets);
        self::assertEquals($obImageSet->id, $obOffer->image_sets->first()->id);
        self::assertEquals(['first.jpg', 'second.jpg'], $obOffer->image_sets->first()->images->pluck('file_name')->all());

        $obImageSet = ImageSet::find($obImageSet->id);
        self::assertInstanceOf(Offer::class, $obImageSet->imageable);
        self::assertEquals($obOffer->id, $obImageSet->imageable->id);
    }

    /**
     * Image set created before offer is saved, is attached to offer with deferred binding
     */
    public function testImageSetsDeferredBinding()
    {
        $sSessionKey = uniqid('session_key', true);

        $obImageSet = new ImageSet();
        $obImageSet->profile_id = $this->createProfile('Tablet', 768)->id;
        $obImageSet->save();
        $obImageSet->images()->add($this->createFile('first.jpg'));

        $obOffer = new Offer();
        $obOffer->name = 'Offer';
        $obOffer->product_id = $this->createProduct()->id;
        $obOffer->image_sets()->add($obImageSet, $sSessionKey);
        $obOffer->save(null, $sSessionKey);

        $obImageSet = ImageSet::find($obImageSet->id);
        self::assertEquals(Offer::class, $obImageSet->imageable_type);
        self::assertEquals($obOffer->id, $obImageSet->imageable_id);
        self::assertCount(1, $obImageSet->images);
    }

    /**
     * Soft delete keeps image sets and files, restore returns them
     */
    public function testSoftDeleteKeepsImageSets()
    {
        $obOffer = $this->createOffer();
        $obImageSet = $this->createImageSet($obOffer, $this->createProfile('Tablet', 768), ['first.jpg', 'second.jpg']);
        $arFileIDList = $obImageSet->images->pluck('id')->all();
        $arFilePathList = $this->getFilePathList($obImageSet);

        $obOffer->delete();
        self::assertNull(Offer::find($obOffer->id));
        $this->assertImageSetExists($obImageSet, $arFileIDList, $arFilePathList);

        Offer::withTrashed()->find($obOffer->id)->restore();
        $obOffer = Offer::find($obOffer->id);
        self::assertNotNull($obOffer);
        self::assertCount(1, $obOffer->image_sets);
        self::assertCount(2, $obOffer->image_sets->first()->images);
        $this->assertImageSetExists($obImageSet, $arFileIDList, $arFilePathList);
    }

    /**
     * Permanent delete removes image sets and files: directly and from trash
     */
    public function testForceDeleteRemovesImageSets()
    {
        $obProfile = $this->createProfile('Tablet', 768);
        $obOffer = $this->createOffer();
        $obTrashedOffer = $this->createOffer('Trashed');
        $obOtherOffer = $this->createOffer('Other');

        $obImageSet = $this->createImageSet($obOffer, $obProfile, ['first.jpg', 'second.jpg']);
        $obTrashedImageSet = $this->createImageSet($obTrashedOffer, $obProfile, ['third.jpg']);
        $obOtherImageSet = $this->createImageSet($obOtherOffer, $obProfile);

        $arFileIDList = $obImageSet->images->pluck('id')->all();
        $arFilePathList = $this->getFilePathList($obImageSet);
        $obOffer->forceDelete();
        $this->assertImageSetRemoved($obImageSet, $arFileIDList, $arFilePathList);

        $arFileIDList = $obTrashedImageSet->images->pluck('id')->all();
        $arFilePathList = $this->getFilePathList($obTrashedImageSet);
        $obTrashedOffer->delete();
        Offer::withTrashed()->find($obTrashedOffer->id)->forceDelete();
        $this->assertImageSetRemoved($obTrashedImageSet, $arFileIDList, $arFilePathList);

        self::assertNotNull(ImageSet::find($obOtherImageSet->id));
        self::assertCount(1, ImageSet::find($obOtherImageSet->id)->images);
    }

    /**
     * Create product
     * @return Product
     */
    protected function createProduct()
    {
        return Product::create([
            'name' => 'Product',
            'slug' => 'product-'.uniqid(),
        ]);
    }

    /**
     * Create offer
     * @param string $sName
     * @return Offer
     */
    protected function createOffer($sName = 'Offer')
    {
        return Offer::create([
            'name'       => $sName,
            'product_id' => $this->createProduct()->id,
        ]);
    }
}
