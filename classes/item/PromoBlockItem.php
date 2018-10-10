<?php namespace Lovata\Shopaholic\Classes\Item;

use Lovata\Toolbox\Classes\Item\ElementItem;

use Lovata\Shopaholic\Models\PromoBlock;
use Lovata\Shopaholic\Classes\Collection\ProductCollection;

/**
 * Class PromoBlockItem
 * @package Lovata\PromoBlocksShopaholic\Classes\Item
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property int                       $id
 * @property string                    $name
 * @property string                    $slug
 * @property string                    $code
 * @property string                    $type
 * @property string $
 * @property \October\Rain\Argon\Argon $date_begin
 * @property \October\Rain\Argon\Argon $date_end
 *
 * @property string                    $preview_text
 * @property \System\Models\File       $preview_image
 *
 * @property string                    $description
 * @property \October\Rain\Database\Collection|\System\Models\File[]  $images
 * @property ProductCollection|\Lovata\Shopaholic\Classes\Item\ProductItem[] $product
 */
class PromoBlockItem extends ElementItem
{
    const MODEL_CLASS = PromoBlock::class;
    
    /** @var PromoBlock */
    protected $obElement = null;

    /**
     * Get product collection attribute
     * @return ProductCollection
     */
    protected function getProductAttribute() : ProductCollection
    {
        $obProductList = ProductCollection::make()->promo($this->id);

        return $obProductList;
    }
}
