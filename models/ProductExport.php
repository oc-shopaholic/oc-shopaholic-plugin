<?php namespace Lovata\Shopaholic\Models;

use System\Classes\PluginManager;

use Lovata\PropertiesShopaholic\Classes\Event\Product\ExtendProductControllerHandler;
use Lovata\Toolbox\Classes\Helper\AbstractExportModelInCSV;

/**
 * Class ProductExport
 *
 * @package Lovata\Shopaholic\Models
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 */
class ProductExport extends AbstractExportModelInCSV
{
    const RELATION_ADDITIONAL_CATEGORY = 'additional_category';

    const RELATION_LIST = [self::RELATION_ADDITIONAL_CATEGORY];

    /** @var string */
    public $table = 'lovata_shopaholic_products';

    /**
     * Get property list.
     * @return array
     */
    protected function getPropertyList() : array
    {
        if (!PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            return [];
        }

        $arPropertyList = ExtendProductControllerHandler::getPropertyListConfig();

        if (empty($arPropertyList)) {
            return [];
        }

        $arPropertyList = array_keys($arPropertyList);

        return $arPropertyList;
    }

    /**
     * Get list.
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Product[]
     */
    protected function getItemList()
    {
        return Product::with($this->arRelationColumnList)->get();
    }

    /**
     * Prepare model relations data.
     * @param Product $obProduct
     * @return array
     */
    protected function prepareModelRelationsData($obProduct) : array
    {
        $arResult = [];

        if (empty($this->arRelationColumnList)) {
            return $arResult;
        }

        if ($obProduct->additional_category->isNotEmpty()
            && in_array(self::RELATION_ADDITIONAL_CATEGORY, $this->arRelationColumnList)
        ) {
            $arAdditionalCategoryIdList = (array) $obProduct->additional_category()->lists('external_id');
            $arAdditionalCategoryIdList = array_filter($arAdditionalCategoryIdList);

            $sAdditionalCategoryIdList = implode(',', $arAdditionalCategoryIdList);

            $arResult[self::RELATION_ADDITIONAL_CATEGORY] = $sAdditionalCategoryIdList;
        }

        return $arResult;
    }
}
