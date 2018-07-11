<?php namespace Lovata\Shopaholic\Models;

use Model;

use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Scope\SlugField;

use October\Rain\Database\Traits\Sluggable;
use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\NestedTree;

use Lovata\Toolbox\Traits\Helpers\TraitCached;

/**
 * Class Category
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
 * @property string $external_id
 * @property string $preview_text
 * @property string $description
 * @property \October\Rain\Argon\Argon $created_at
 * @property \October\Rain\Argon\Argon $updated_at
 *
 * Nested tree properties
 * @property int $parent_id
 * @property int $nest_left
 * @property int $nest_right
 * @property int $nest_depth
 * @property Category $parent
 * @property \October\Rain\Database\Collection|Category[] $children
 * @method static $this children()
 *
 * Relations
 * @property \System\Models\File $preview_image
 * @property \October\Rain\Database\Collection|\System\Models\File[] $images
 *
 * @property \October\Rain\Database\Collection|Product[] $product
 * @method \October\Rain\Database\Relations\HasMany|Product product()
 *
 * @property \October\Rain\Database\Collection|Product[] $product_link
 * @method static \October\Rain\Database\Relations\BelongsToMany|Product product_link()
 *
 * Properties for Shopaholic
 * @see \Lovata\PropertiesShopaholic\Classes\Event\CategoryModelHandler::addModelRelationConfig
 *
 * @property \October\Rain\Database\Collection|\Lovata\PropertiesShopaholic\Models\PropertySet[] $property_set
 * @method static \October\Rain\Database\Relations\BelongsToMany|\Lovata\PropertiesShopaholic\Models\PropertySet property_set()
 *
 * @property \October\Rain\Database\Collection|\Lovata\PropertiesShopaholic\Models\Property[] $product_property
 * @property \October\Rain\Database\Collection|\Lovata\PropertiesShopaholic\Models\Property[] $offer_property
 *
 * @method static $this getByParentID(int $iParentID)
 */
class Category extends Model
{
    use Validation;
    use NestedTree;
    use Sluggable;
    use ActiveField;
    use NameField;
    use SlugField;
    use CodeField;
    use ExternalIDField;
    use TraitCached;

    public $table = 'lovata_shopaholic_categories';

    public $implement = [
        '@RainLab.Translate.Behaviors.TranslatableModel',
    ];

    public $translatable = ['name', 'preview_text', 'description'];

    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_categories',
    ];

    public $attributeNames = [
        'name' => 'lovata.toolbox::lang.field.name',
        'slug' => 'lovata.toolbox::lang.field.slug',
    ];

    public $slugs = ['slug' => 'name'];

    public $attachOne = ['preview_image' => 'System\Models\File'];
    public $attachMany = ['images' => 'System\Models\File'];
    public $belongsToMany = [
        'product_link' => [
            Product::class,
            'table' => 'lovata_shopaholic_additional_categories',
        ],
    ];
    public $hasMany = ['product' => Product::class];

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
    ];

    public $cached = [
        'id',
        'name',
        'slug',
        'code',
        'preview_text',
        'description',
        'parent_id',
        'preview_image',
        'images',
        'updated_at',
    ];

    public $dates = ['created_at', 'updated_at'];
    public $casts = [];

    /**
     * Get by parent ID
     * @param Category $obQuery
     * @param string   $sData
     * @return Category
     */
    public function scopeGetByParentID($obQuery, $sData)
    {
        return $obQuery->where('parent_id', $sData);
    }
}
