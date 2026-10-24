<?php namespace Lovata\Shopaholic\Tests\Fixtures;

use DB;
use Config;
use Storage;
use System\Models\File;
use October\Rain\Filesystem\Filesystem;

use Lovata\Toolbox\Models\Settings;
use Lovata\Toolbox\Models\ImageSet;
use Lovata\Toolbox\Models\ImageProfile;

/**
 * Trait ImageSetsTestTrait
 * Common setup of image sets tests: settings, profiles, sets and uploads folder
 * @package Lovata\Shopaholic\Tests\Fixtures
 * @author  Pavel Lautsevich, p.lautsevich@lovata.com, LOVATA Group
 *
 * @mixin \PluginTestCase
 */
trait ImageSetsTestTrait
{
    /** @var string */
    protected $sUploadsPath;

    /**
     * Init uploads folder and default settings
     */
    protected function setUpImageSets()
    {
        //Uploaded files are stored in temp folder, not in project storage
        $this->sUploadsPath = temp_path('shopaholic-image-sets-tests-'.uniqid());
        Config::set('filesystems.disks.uploads.root', $this->sUploadsPath);
        Storage::forgetDisk('uploads');

        //Table "system_files" is not rolled back with plugin tables, so files of image sets from previous tests are removed
        $this->removeImageSetFiles();

        $this->setSettings(false, ImageProfile::APPROACH_DESKTOP_FIRST);
    }

    /**
     * Remove uploaded files
     */
    protected function tearDownImageSets()
    {
        $this->removeImageSetFiles();

        if (!empty($this->sUploadsPath)) {
            (new Filesystem())->deleteDirectory($this->sUploadsPath);
        }
    }

    /**
     * Remove file records of image sets
     */
    protected function removeImageSetFiles()
    {
        DB::table('system_files')->where('attachment_type', ImageSet::class)->delete();
    }

    /**
     * Set image settings
     * @param bool   $bEnabled
     * @param string $sApproach
     */
    protected function setSettings($bEnabled, $sApproach)
    {
        Settings::clearInternalCache();
        Settings::set([
            'image_sets_enabled'      => $bEnabled,
            'image_profiles_approach' => $sApproach,
        ]);
        Settings::clearInternalCache();
    }

    /**
     * Create image profile
     * @param string $sName
     * @param int    $iWidth
     * @param bool   $bActive
     * @return ImageProfile
     */
    protected function createProfile($sName, $iWidth, $bActive = true)
    {
        $obProfile = new ImageProfile();
        $obProfile->name = $sName;
        $obProfile->width = $iWidth;
        $obProfile->active = $bActive;
        $obProfile->save();

        return $obProfile;
    }

    /**
     * Create image set with files
     * @param \Model       $obElement
     * @param ImageProfile $obProfile
     * @param array        $arFileNameList
     * @return ImageSet
     */
    protected function createImageSet($obElement, $obProfile, $arFileNameList = ['image.jpg'])
    {
        $obImageSet = new ImageSet();
        $obImageSet->profile_id = $obProfile->id;
        $obImageSet->imageable_type = get_class($obElement);
        $obImageSet->imageable_id = $obElement->id;
        $obImageSet->save();

        foreach ($arFileNameList as $sFileName) {
            $obImageSet->images()->add($this->createFile($sFileName));
        }

        return $obImageSet;
    }

    /**
     * Create file
     * @param string $sFileName
     * @return File
     */
    protected function createFile($sFileName)
    {
        $obFile = new File();
        $obFile->fromData('test-'.$sFileName, $sFileName);
        $obFile->is_public = true;
        $obFile->save();

        return $obFile;
    }

    /**
     * Get width list of image sets from Item
     * @param \Lovata\Toolbox\Classes\Item\ElementItem $obItem
     * @return array
     */
    protected function getWidthList($obItem)
    {
        return array_column(array_column($obItem->image_sets, 'profile'), 'width');
    }

    /**
     * Get file name list of image set from Item
     * @param array $arImageSet
     * @return array
     */
    protected function getFileNameList($arImageSet)
    {
        return array_map(function ($obFile) {
            return $obFile->file_name;
        }, $arImageSet['images']);
    }

    /**
     * Get image sets data of Item for comparison
     * @param \Lovata\Toolbox\Classes\Item\ElementItem $obItem
     * @return array
     */
    protected function getImageSetsSnapshot($obItem)
    {
        $arResult = [];
        foreach ($obItem->image_sets as $arImageSet) {
            $arFileList = [];
            foreach ($arImageSet['images'] as $obFile) {
                $arFileList[] = [
                    'id'        => $obFile->id,
                    'disk_name' => $obFile->disk_name,
                    'file_name' => $obFile->file_name,
                    'path'      => $obFile->path,
                ];
            }

            $arResult[] = [
                'profile' => $arImageSet['profile'],
                'images'  => $arFileList,
            ];
        }

        return $arResult;
    }

    /**
     * Check that image set and its files exist in DB and on disk
     * @param ImageSet $obImageSet
     * @param array    $arFileIDList
     * @param array    $arFilePathList
     */
    protected function assertImageSetExists($obImageSet, $arFileIDList, $arFilePathList)
    {
        $this->assertNotNull(ImageSet::find($obImageSet->id));
        $this->assertEquals(count($arFileIDList), File::whereIn('id', $arFileIDList)->count());
        foreach ($arFilePathList as $sFilePath) {
            $this->assertFileExists($sFilePath);
        }
    }

    /**
     * Check that image set and its files are removed from DB and from disk
     * @param ImageSet $obImageSet
     * @param array    $arFileIDList
     * @param array    $arFilePathList
     */
    protected function assertImageSetRemoved($obImageSet, $arFileIDList, $arFilePathList)
    {
        $this->assertNull(ImageSet::find($obImageSet->id));
        $this->assertEquals(0, File::whereIn('id', $arFileIDList)->count());
        foreach ($arFilePathList as $sFilePath) {
            $this->assertFileDoesNotExist($sFilePath);
        }
    }

    /**
     * Get local path list of image set files
     * @param ImageSet $obImageSet
     * @return array
     */
    protected function getFilePathList($obImageSet)
    {
        return $obImageSet->images->map(function ($obFile) {
            return $obFile->getLocalPath();
        })->all();
    }
}
