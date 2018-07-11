<?php namespace Lovata\Shopaholic\Models;

use Model;

use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CategoryBelongsTo;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Scope\SlugField;

use October\Rain\Database\Traits\Sluggable;
use October\Rain\Database\Traits\SoftDelete;
use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\Purgeable;

use Lovata\Toolbox\Traits\Helpers\TraitCached;

/**
 * Class Product
 * @package Lovata\Shopaholic\Models
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property                                                                             $id
 * @property bool                                                                        $active
 * @property string                                                                      $name
 * @property string                                                                      $slug
 * @property string                                                                      $code
 * @property int                                                                         $category_id
 * @property int                                                                         $brand_id
 * @property string                                                                      $external_id
 * @property string                                                                      $preview_text
 * @property string                                                                      $description
 * @property \October\Rain\Argon\Argon                                                   $created_at
 * @property \October\Rain\Argon\Argon                                                   $updated_at
 * @property \October\Rain\Argon\Argon                                                   $deleted_at
 *
 * Relations
 * @property \System\Models\File                                                         $preview_image
 * @property \October\Rain\Database\Collection|\System\Models\File[]                     $images
 *
 * @property Category                                                                    $category
 * @method static \October\Rain\Database\Relations\BelongsTo|Category category()
 *
 * @property \October\Rain\Database\Collection|Category[]                                $additional_category
 * @method static \October\Rain\Database\Relations\BelongsToMany|Category additional_category()
 *
 * @property Brand                                                                       $brand
 * @method static \October\Rain\Database\Relations\BelongsTo|Brand brand()
 *
 * @property \October\Rain\Database\Collection|Offer[]                                   $offer
 * @method \October\Rain\Database\Relations\HasMany|Offer offer()
 *
 * @method static $this getByBrand(int $iBrandID)
 *
 * Properties for Shopaholic
 * @see     \Lovata\PropertiesShopaholic\Classes\Event\ProductModelHandler::addPropertyMethods
 * @property array                                                                       $property
 *
 * @property \October\Rain\Database\Collection|\Lovata\PropertiesShopaholic\Models\PropertyValueLink[] $property_value
 * @method static \October\Rain\Database\Relations\MorphMany|\Lovata\PropertiesShopaholic\Models\PropertyValueLink property_value()
 *
 * Popularity for Shopaholic
 * @property int                                                                         $popularity
 *
 * Reviews for Shopaholic
 * @property float                                                                       $rating
 * @property array                                                                       $rating_data
 * @property \October\Rain\Database\Collection|\Lovata\ReviewsShopaholic\Models\Review[] $review
 * @method static \October\Rain\Database\Relations\HasMany|\Lovata\ReviewsShopaholic\Models\Review review()
 *
 * Related products for Shopaholic
 * @property \October\Rain\Database\Collection|Product[]                                 $related
 * @method static \October\Rain\Database\Relations\BelongsToMany|$this related()
 *
 * Accessories for Shopaholic
 * @property \October\Rain\Database\Collection|Product[]                                 $accessory
 * @method static \October\Rain\Database\Relations\BelongsToMany|$this accessory()
 *
 * Search for Shopaholic, Sphinx for Shopaholic
 * @property string                                                                      $search_synonym
 * @property string                                                                      $search_content
 */
class Product extends Model
{
    use Validation;
    use SoftDelete;
    use Sluggable;
    use Purgeable;
    use ActiveField;
    use NameField;
    use CategoryBelongsTo;
    use SlugField;
    use CodeField;
    use ExternalIDField;
    use TraitCached;

    public $table = 'lovata_shopaholic_products';

    public $implement = [
        '@RainLab.Translate.Behaviors.TranslatableModel',
    ];

    public $translatable = ['name', 'preview_text', 'description'];

    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_products',
    ];

    public $attributeNames = [
        'name' => 'lovata.toolbox::lang.field.name',
        'slug' => 'lovata.toolbox::lang.field.slug',
    ];

    public $slugs = ['slug' => 'name'];

    public $attachOne = ['preview_image' => 'System\Models\File'];
    public $attachMany = ['images' => 'System\Models\File'];
    public $hasMany = ['offer' => [Offer::class]];
    public $belongsTo = [
        'category' => [Category::class],
        'brand'    => [Brand::class],
    ];
    public $belongsToMany = [
        'additional_category' => [
            Category::class,
            'table'      => 'lovata_shopaholic_additional_categories',
        ],
    ];

    public $morphMany = [];

    public $appends = [];
    public $purgeable = [];
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

    public $cached = [
        'id',
        'active',
        'name',
        'slug',
        'code',
        'category_id',
        'brand_id',
        'preview_text',
        'preview_image',
        'description',
        'images',
    ];

    public $dates = ['created_at', 'updated_at', 'deleted_at'];

    public $jsonable = [];

    /**
     * Get element by brand ID
     * @param Product $obQuery
     * @param string  $sData
     * @return $this
     */
    public function scopeGetByBrand($obQuery, $sData)
    {
        if (!empty($sData)) {
            $obQuery->where('brand_id', $sData);
        }

        return $obQuery;
    }
}
