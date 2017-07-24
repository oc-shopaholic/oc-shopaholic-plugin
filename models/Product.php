<?php namespace Lovata\Shopaholic\Models;

use Model;

use Kharanenka\Helper\CustomValidationMessage;
use Kharanenka\Helper\DataFileModel;
use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CategoryBelongsTo;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Scope\SlugField;

use Lovata\Toolbox\Plugin as ToolboxPlugin;

use October\Rain\Database\Traits\Sluggable;
use October\Rain\Database\Traits\SoftDelete;
use October\Rain\Database\Traits\Validation;

/**
 * Class Product
 * @package Lovata\Shopaholic\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 * 
 * @property $id
 * @property bool $active
 * @property string $name
 * @property string $slug
 * @property string $code
 * @property int $category_id
 * @property int $brand_id
 * @property string $external_id
 * @property string $preview_text
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * Relations
 * @property \System\Models\File $preview_image
 * @property \October\Rain\Database\Collection|\System\Models\File[] $images
 *
 * @property Category $category
 * @method static \October\Rain\Database\Relations\BelongsTo category()
 *
 * @property Brand $brand
 * @method static \October\Rain\Database\Relations\BelongsTo brand()
 *
 * @property \October\Rain\Database\Collection|Offer[] $offer
 * @method Offer|\October\Rain\Database\Relations\HasMany offer()
 *
 * @method static $this getByBrand(int $iBrandID)
 *
 * Stickers for Shopaholic
 * @property \Lovata\StickersShopaholic\Models\Sticker[]|\October\Rain\Database\Collection $sticker
 * @method static \Lovata\StickersShopaholic\Models\Sticker|\October\Rain\Database\Relations\BelongsToMany sticker()
 *
 * Popularity for Shopaholic
 * @property int $popularity
 *
 * Reviews for Shopaholic
 * @property \Lovata\ReviewsShopaholic\Models\Review[]|\October\Rain\Database\Collection $reviews
 * @method \Lovata\ReviewsShopaholic\Models\Review|\October\Rain\Database\Relations\HasMany reviews()
 *
 * Properties for Shopaholic fields
 * @property array $property
 * @property array $property_value
 * 
 */
class Product extends Model
{
    use Validation;
    use SoftDelete;
    use Sluggable;
    use ActiveField;
    use NameField;
    use CategoryBelongsTo;
    use SlugField;
    use CodeField;
    use ExternalIDField;
    use CustomValidationMessage;
    use DataFileModel;

    public $table = 'lovata_shopaholic_products';
  
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_products',
    ];

    public $customMessages = [];
    public $attributeNames = [];

    public $slugs = ['slug' => 'name'];
    
    public $attachOne = ['preview_image' => 'System\Models\File'];
    public $attachMany = ['images' => 'System\Models\File'];
    public $hasMany = ['offer' => ['Lovata\Shopaholic\Models\Offer']];
    public $belongsTo = [
        'category' => ['Lovata\Shopaholic\Models\Category'],
        'brand'    => ['Lovata\Shopaholic\Models\Brand'],
    ];

    public $appends = [];
    public $fillable = [
        'name',
        'slug',
        'active',
        'code',
        'external_id',
        'preview_text',
        'description',
        'brand_id',
        'category_id',
    ];

    public $dates = ['created_at', 'created_at', 'deleted_at'];

    /**
     * Product constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $iPreviewTextMaxLength = (int) Settings::getValue('product_preview_limit_max');
        if($iPreviewTextMaxLength > 0) {
            $this->rules['preview_text'] = 'max:'.$iPreviewTextMaxLength;
        }

        $this->setCustomMessage(ToolboxPlugin::NAME, ['required', 'unique', 'max.string']);
        $this->setCustomAttributeName(ToolboxPlugin::NAME, ['name', 'slug', 'preview_text']);

        parent::__construct($attributes);
    }

    /**
     * Get element by brand ID
     * @param Product $obQuery
     * @param string $sData
     * @return $this
     */
    public function scopeGetByBrand($obQuery, $sData) {

        if(!empty($sData)) {
            $obQuery->where('brand_id', $sData);
        }

        return $obQuery;
    }
}
