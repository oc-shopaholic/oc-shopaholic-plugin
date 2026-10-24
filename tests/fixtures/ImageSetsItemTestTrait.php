<?php namespace Lovata\Shopaholic\Tests\Fixtures;

use DB;
use System\Models\File;

use Lovata\Toolbox\Models\ImageProfile;
use Lovata\Toolbox\Classes\Item\ItemStorage;

/**
 * Trait ImageSetsItemTestTrait
 * Common tests of "image_sets" field of Item class.
 * Test class must define $sImageSetsItemClass and createImageSetsElement() method.
 * @package Lovata\Shopaholic\Tests\Fixtures
 * @author  Pavel Lautsevich, p.lautsevich@lovata.com, LOVATA Group
 *
 * @mixin \Lovata\Toolbox\Tests\CommonTest
 * @mixin ImageSetsTestTrait
 */
trait ImageSetsItemTestTrait
{
    /**
     * Image sets are returned in Toolbox format, ordered by approach, without sets of inactive profiles
     */
    public function testImageSetsFormatAndOrder()
    {
        $sItemClass = $this->sImageSetsItemClass;
        list($obElement, $arProfileList) = $this->createElementWithImageSets();

        $obItem = $sItemClass::make($obElement->id);
        $this->assertEquals([480, 1024, 1440], $this->getWidthList($obItem));
        $this->assertEquals([
            'id'    => $arProfileList['mobile']->id,
            'name'  => 'Mobile',
            'width' => 480,
            'media' => '(width < 480px)',
        ], $obItem->image_sets[0]['profile']);
        $this->assertEquals(['mobile-1.jpg', 'mobile-2.jpg'], $this->getFileNameList($obItem->image_sets[0]));
        $this->assertContainsOnlyInstancesOf(File::class, $obItem->image_sets[0]['images']);

        //Sets of inactive profile are skipped
        $obProfile = $arProfileList['tablet'];
        $obProfile->active = false;
        $obProfile->save();
        $this->assertEquals([480, 1440], $this->getWidthList($sItemClass::make($obElement->id)));

        //Sets are ordered by descending width for "mobile_first"
        $this->setSettings(true, ImageProfile::APPROACH_MOBILE_FIRST);
        $obItem = $sItemClass::make($obElement->id);
        $this->assertEquals([1440, 480], $this->getWidthList($obItem));
        $this->assertEquals('(width >= 1440px)', $obItem->image_sets[0]['profile']['media']);

        //Element without sets returns empty list
        $obEmptyItem = $sItemClass::make($this->createImageSetsElement('Empty')->id);
        $this->assertSame([], $obEmptyItem->image_sets);
    }

    /**
     * Item built from cache returns the same sets, profiles and files as Item built from model
     */
    public function testImageSetsItemFromCacheIsEqualToItemFromModel()
    {
        $sItemClass = $this->sImageSetsItemClass;
        list($obElement) = $this->createElementWithImageSets();

        $arModelData = $this->getImageSetsSnapshot($sItemClass::makeNoCache($obElement->id));

        //Item is built from model and saved in cache
        $this->assertEquals($arModelData, $this->getImageSetsSnapshot($sItemClass::make($obElement->id)));

        //Data is changed without model events: Item must be read from cache
        DB::table('lovata_toolbox_image_profiles')->update(['name' => 'Changed']);
        ItemStorage::clear($sItemClass, $obElement->id);

        $obCachedItem = $sItemClass::make($obElement->id);
        $this->assertEquals($arModelData, $this->getImageSetsSnapshot($obCachedItem));
        foreach ($obCachedItem->image_sets as $arImageSet) {
            $this->assertContainsOnlyInstancesOf(File::class, $arImageSet['images']);
        }
    }

    /**
     * Item cache is cleared when set is saved or deleted, profile is changed and approach is switched
     */
    public function testImageSetsCacheIsCleared()
    {
        $sItemClass = $this->sImageSetsItemClass;
        list($obElement, $arProfileList) = $this->createElementWithImageSets();
        $this->assertCount(3, $sItemClass::make($obElement->id)->image_sets);

        //Set is saved
        $obImageSet = $this->createImageSet($obElement, $this->createProfile('Wide', 1920));
        $this->assertEquals([480, 1024, 1440, 1920], $this->getWidthList($sItemClass::make($obElement->id)));

        //Set is deleted
        $obImageSet->delete();
        $this->assertEquals([480, 1024, 1440], $this->getWidthList($sItemClass::make($obElement->id)));

        //Profile is changed
        $obProfile = $arProfileList['tablet'];
        $obProfile->width = 400;
        $obProfile->name = 'Small tablet';
        $obProfile->save();
        $obItem = $sItemClass::make($obElement->id);
        $this->assertEquals([400, 480, 1440], $this->getWidthList($obItem));
        $this->assertEquals('Small tablet', $obItem->image_sets[0]['profile']['name']);

        //Approach is switched
        $this->setSettings(true, ImageProfile::APPROACH_MOBILE_FIRST);
        $this->assertEquals([1440, 480, 400], $this->getWidthList($sItemClass::make($obElement->id)));
    }

    /**
     * Example of <picture> tag from documentation is rendered by Twig
     */
    public function testImageSetsTwigExample()
    {
        $sItemClass = $this->sImageSetsItemClass;
        list($obElement) = $this->createElementWithImageSets();

        $sTemplate = '{% for obSet in obElement.image_sets %}'
            .'<source media="{{ obSet.profile.media }}" srcset="{{ (obSet.images|first).path }}">'
            .'{% endfor %}';

        $obItem = $sItemClass::make($obElement->id);
        $sResult = app('twig.environment')->createTemplate($sTemplate)->render(['obElement' => $obItem]);

        $sExpectedResult = '';
        foreach ($obItem->image_sets as $arImageSet) {
            $sExpectedResult .= '<source media="'.e($arImageSet['profile']['media']).'" srcset="'.e($arImageSet['images'][0]->path).'">';
        }

        $this->assertEquals($sExpectedResult, $sResult);
        $this->assertStringContainsString('media="(width &lt; 480px)"', $sResult);
    }

    /**
     * Create element with image sets of three profiles. Profiles are created not in width order.
     * @return array
     */
    protected function createElementWithImageSets()
    {
        $this->setSettings(true, ImageProfile::APPROACH_DESKTOP_FIRST);

        $arProfileList = [
            'tablet' => $this->createProfile('Tablet', 1024),
            'mobile' => $this->createProfile('Mobile', 480),
            'laptop' => $this->createProfile('Laptop', 1440),
        ];

        $obElement = $this->createImageSetsElement();
        foreach ($arProfileList as $sCode => $obProfile) {
            $this->createImageSet($obElement, $obProfile, [$sCode.'-1.jpg', $sCode.'-2.jpg']);
        }

        return [$obElement, $arProfileList];
    }

    /**
     * Create element for image sets tests
     * @param string $sName
     * @return \Model
     */
    abstract protected function createImageSetsElement($sName = 'Element');
}
