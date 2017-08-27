<?php namespace Lovata\Shopaholic\Models;

use Model;

use Kharanenka\Helper\DataFileModel;
use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CategoryBelongsTo;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Scope\SlugField;

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
 * @method static \October\Rain\Database\Relations\BelongsTo|Category category()
 *
 * @property Brand $brand
 * @method static \October\Rain\Database\Relations\BelongsTo|Brand brand()
 *
 * @property \October\Rain\Database\Collection|Offer[] $offer
 * @method \October\Rain\Database\Relations\HasMany|Offer offer()
 *
 * @method static $this getByBrand(int $iBrandID)
 *
 * Properties for Shopaholic
 * @see \Lovata\PropertiesShopaholic\Classes\Event\ProductModelHandler::addPropertyMethods
 * @property array $property
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
    use DataFileModel;

    public $table = 'lovata_shopaholic_products';
  
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_products',
    ];

    public $attributeNames = [
        'lovata.toolbox::lang.field.name',
        'lovata.toolbox::lang.field.slug',
    ];

    public $slugs = ['slug' => 'name'];
    
    public $attachOne = ['preview_image' => 'System\Models\File'];
    public $attachMany = ['images' => 'System\Models\File'];
    public $hasMany = ['offer' => [Offer::class]];
    public $belongsTo = [
        'category' => [Category::class],
        'brand'    => [Brand::class],
    ];

    public $appends = [];
    public $fillable = [
        'active',
        'name',
        'slug',
        'code',
        'external_id',
        'preview_text',
        'description',
        'brand_id',
        'category_id',
    ];

    public $dates = ['created_at', 'created_at', 'deleted_at'];

    public $jsonable = [];

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
