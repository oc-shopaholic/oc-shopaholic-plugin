<?php namespace Lovata\Shopaholic\Models;

use Model;

use Kharanenka\Helper\DataFileModel;
use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Scope\SlugField;

use October\Rain\Database\Traits\Sluggable;
use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\NestedTree;

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
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
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
 * Properties for Shopaholic
 * @see \Lovata\PropertiesShopaholic\Classes\Event\CategoryModelHandler::addModelRelationConfig
 *
 * @property \October\Rain\Database\Collection|\Lovata\PropertiesShopaholic\Models\Property $product_property
 * @method static \October\Rain\Database\Relations\BelongsToMany|\Lovata\PropertiesShopaholic\Models\Property product_property()
 *
 * @property \October\Rain\Database\Collection|\Lovata\PropertiesShopaholic\Models\Property $offer_property
 * @method static \October\Rain\Database\Relations\BelongsToMany|\Lovata\PropertiesShopaholic\Models\Property offer_property()
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
    use DataFileModel;

    public $table = 'lovata_shopaholic_categories';
    
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_categories',
    ];

    public $attributeNames = [
        'lovata.toolbox::lang.field.name',
        'lovata.toolbox::lang.field.slug',
    ];

    public $slugs = ['slug' => 'name'];
    
    public $attachOne = ['preview_image' => 'System\Models\File'];
    public $attachMany = ['images' => 'System\Models\File'];
    public $belongsToMany = [];
    public $hasMany = ['product' => Product::class];

    public $fillable = [
        'active',
        'name',
        'slug',
        'code',
        'external_id',
        'preview_text',
        'description',
    ];

    public $dates = ['created_at', 'updated_at'];
    public $casts = [];

    /**
     * Get by parent ID
     * @param Category $obQuery
     * @param $sData
     * @return Category
     */
    public function scopeGetByParentID($obQuery, $sData)
    {
        return $obQuery->where('parent_id', $sData);
    }
}