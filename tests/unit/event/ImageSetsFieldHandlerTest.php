<?php namespace Lovata\Shopaholic\Tests\Unit\Event;

use Event;
use Backend\Widgets\Form;
use Backend\FormWidgets\Repeater;

use Lovata\Toolbox\Tests\CommonTest;
use Lovata\Toolbox\Models\ImageProfile;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Controllers\Offers;
use Lovata\Shopaholic\Controllers\Products;
use Lovata\Shopaholic\Tests\Fixtures\ImageSetsTestTrait;
use Lovata\Shopaholic\Classes\Event\Offer\ExtendOfferImageSetsFieldHandler;
use Lovata\Shopaholic\Classes\Event\Product\ExtendProductImageSetsFieldHandler;
use Lovata\Shopaholic\Classes\Event\Offer\ExtendOfferImageSetsInProductFieldHandler;

/**
 * Class ImageSetsFieldHandlerTest
 * @package Lovata\Shopaholic\Tests\Unit\Event
 * @author  Pavel Lautsevich, p.lautsevich@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class ImageSetsFieldHandlerTest extends CommonTest
{
    use ImageSetsTestTrait;

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
     * Field is added to product, offer and "Create offer" popup forms only when image sets are enabled and active profile exists
     */
    public function testFieldIsAddedWhenEnabledAndActiveProfileExists()
    {
        $arFormList = [
            'create product' => [Products::class, Product::class, 'create'],
            'update product' => [Products::class, Product::class, 'update'],
            'update offer'   => [Offers::class, Offer::class, 'update'],
            'create offer'   => [Products::class, Offer::class, 'create'],
        ];

        $this->subscribeHandlers([
            ExtendProductImageSetsFieldHandler::class,
            ExtendOfferImageSetsFieldHandler::class,
            ExtendOfferImageSetsInProductFieldHandler::class,
        ]);

        //Disabled, no profiles
        $this->assertFieldIsNotAdded($arFormList);

        //Enabled, no profiles
        $this->setSettings(true, ImageProfile::APPROACH_DESKTOP_FIRST);
        $this->assertFieldIsNotAdded($arFormList);

        //Enabled, only inactive profile
        $obProfile = $this->createProfile('Tablet', 768, false);
        $this->assertFieldIsNotAdded($arFormList);

        //Disabled, active profile
        $obProfile->active = true;
        $obProfile->save();
        $this->setSettings(false, ImageProfile::APPROACH_DESKTOP_FIRST);
        $this->assertFieldIsNotAdded($arFormList);

        //Enabled, active profile
        $this->setSettings(true, ImageProfile::APPROACH_DESKTOP_FIRST);
        foreach ($arFormList as $sFormName => $arFormData) {
            list($sControllerClass, $sModelClass, $sContext) = $arFormData;
            $obForm = $this->makeForm($sControllerClass, $sModelClass, $sContext);
            $obField = $obForm->getField('image_sets');

            self::assertNotNull($obField, 'Field is not added to form: '.$sFormName);
            self::assertInstanceOf(Repeater::class, $obForm->getFormWidget('image_sets'));
            self::assertEquals('lovata.toolbox::lang.tab.images', $obField->tab);
            self::assertNotNull($obForm->getField('image_sets_section'));

            //Field is added below existing image fields
            self::assertGreaterThan($obForm->getField('preview_image')->order, $obField->order);
            self::assertGreaterThan($obForm->getField('images')->order, $obField->order);
        }
    }

    /**
     * Each handler adds field only to its own form
     */
    public function testHandlerExtendsOnlyOwnForm()
    {
        $this->setSettings(true, ImageProfile::APPROACH_DESKTOP_FIRST);
        $this->createProfile('Tablet', 768);

        $arHandlerList = [
            ExtendProductImageSetsFieldHandler::class        => [Products::class, Product::class],
            ExtendOfferImageSetsFieldHandler::class          => [Offers::class, Offer::class],
            ExtendOfferImageSetsInProductFieldHandler::class => [Products::class, Offer::class],
        ];

        $arFormList = [
            [Products::class, Product::class],
            [Offers::class, Offer::class],
            [Products::class, Offer::class],
            [Offers::class, Product::class],
        ];

        foreach ($arHandlerList as $sHandlerClass => $arOwnForm) {
            $this->subscribeHandlers([$sHandlerClass]);

            foreach ($arFormList as $arFormData) {
                list($sControllerClass, $sModelClass) = $arFormData;
                $obForm = $this->makeForm($sControllerClass, $sModelClass, 'create');
                $sMessage = $sHandlerClass.': '.$sControllerClass.' + '.$sModelClass;

                if ($arFormData == $arOwnForm) {
                    self::assertNotNull($obForm->getField('image_sets'), $sMessage);
                } else {
                    self::assertNull($obForm->getField('image_sets'), $sMessage);
                }
            }
        }
    }

    /**
     * Leave only tested handlers: fields of other plugins (for example, SEO fields) are not added to test forms
     * @param array $arHandlerClassList
     */
    protected function subscribeHandlers($arHandlerClassList)
    {
        Event::forget('backend.form.extendFields');
        foreach ($arHandlerClassList as $sHandlerClass) {
            Event::subscribe(new $sHandlerClass());
        }
    }

    /**
     * Check that field is not added to forms
     * @param array $arFormList
     */
    protected function assertFieldIsNotAdded($arFormList)
    {
        foreach ($arFormList as $sFormName => $arFormData) {
            list($sControllerClass, $sModelClass, $sContext) = $arFormData;
            $obForm = $this->makeForm($sControllerClass, $sModelClass, $sContext);

            self::assertNull($obForm->getField('image_sets'), 'Field is added to form: '.$sFormName);
            self::assertNull($obForm->getField('image_sets_section'), 'Section is added to form: '.$sFormName);
        }
    }

    /**
     * Make form widget with image fields of model
     * @param string $sControllerClass
     * @param string $sModelClass
     * @param string $sContext
     * @return Form
     */
    protected function makeForm($sControllerClass, $sModelClass, $sContext)
    {
        $obForm = new Form(new $sControllerClass(), [
            'model'     => $sContext == 'create' ? new $sModelClass() : $this->createElement($sModelClass),
            'context'   => $sContext,
            'arrayName' => class_basename($sModelClass),
            'tabs'      => [
                'fields' => [
                    'preview_image' => [
                        'label' => 'lovata.toolbox::lang.field.preview_image',
                        'type'  => 'fileupload',
                        'mode'  => 'image',
                        'tab'   => 'lovata.toolbox::lang.tab.images',
                    ],
                    'images'        => [
                        'label' => 'lovata.toolbox::lang.field.images',
                        'type'  => 'fileupload',
                        'mode'  => 'image',
                        'tab'   => 'lovata.toolbox::lang.tab.images',
                    ],
                ],
            ],
        ]);
        $obForm->bindToController();

        return $obForm;
    }

    /**
     * Create product or offer
     * @param string $sModelClass
     * @return Product|Offer
     */
    protected function createElement($sModelClass)
    {
        $obProduct = Product::create([
            'name' => 'Product',
            'slug' => 'product-'.uniqid(),
        ]);

        if ($sModelClass == Product::class) {
            return $obProduct;
        }

        return Offer::create([
            'name'       => 'Offer',
            'product_id' => $obProduct->id,
        ]);
    }
}
