<?php namespace Lovata\Shopaholic\Classes;

use October\Rain\Database\Collection;
use System\Models\File;

/**
 * Trait CommonModel
 * @package Lovata\Shopaholic\Classes
 * @author Denis Plisko, d.plisko@lovata.com, LOVATA Group
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @method static $this active()
 * @method static $this name(string $sName)
 * @method static $this slug(string $sSlug)
 * @method static $this code(string $sCode)
 * @method static $this externalId($sExternalId)
 */
trait CommonTrait
{
    //TODO: Перенести логику в пакеты
    /**
     * Get active element
     * @param \October\Rain\Database\QueryBuilder $query
     * @return \October\Rain\Database\QueryBuilder
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Get element by name
     * @param \October\Rain\Database\QueryBuilder $obQuery
     * @param $sData
     * @return \October\Rain\Database\QueryBuilder
     */
    public function scopeName($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->where('name', $sData);
        }
        return $obQuery;
    }

    /**
     * Get element by slug
     * @param \October\Rain\Database\QueryBuilder $obQuery
     * @param $sData
     * @return \October\Rain\Database\QueryBuilder
     */
    public function scopeSlug($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->where('slug', $sData);
        }
        return $obQuery;
    }

    /**
     * Get element by code
     * @param \October\Rain\Database\QueryBuilder $obQuery
     * @param $sData
     * @return \October\Rain\Database\QueryBuilder
     */
    public function scopeCode($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->where('code', $sData);
        }
        return $obQuery;
    }

    /**
     * Get element by external ID
     * @param \October\Rain\Database\QueryBuilder $obQuery
     * @param $sData
     * @return \October\Rain\Database\QueryBuilder
     */
    public function scopeExternalId($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->where('external_id', $sData);
        }
        return $obQuery;
    }
    
    /**
     * Get image data
     * @param \System\Models\File $obImage
     * @return array
     */
    public static function getImage($obImage)
    {
        if(empty($obImage) || !$obImage instanceof File){
            return [];
        }

        return [
            'path' => $obImage->getPath(),
            'title' => $obImage->getAttribute('title'),
            'desc' => $obImage->getAttribute('description'),
        ];
    }

    /**
     * Get image list
     * @param Collection $obImages
     * @return array
     */
    public static function getImageList($obImages)
    {
        if($obImages->isEmpty()){
            return [];
        }

        $arResult = [];
        /** @var File $obImage */
        foreach ($obImages as $obImage) {
            if(!$obImage instanceof File) {
                continue;
            }

            $arImageData = self::getImage($obImage);
            if(!empty($arImageData)) {
                $arResult[] = $arImageData;
            }
        }
        
        return $arResult;
    }
}