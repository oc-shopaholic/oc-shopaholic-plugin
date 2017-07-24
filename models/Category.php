<?php namespace Lovata\Shopaholic\Models;

use Model;

use Kharanenka\Helper\CustomValidationMessage;
use Kharanenka\Helper\DataFileModel;
use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Scope\SlugField;

use Lovata\Toolbox\Plugin as ToolboxPlugin;

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
 * @property \October\Rain\Database\Collection|Product[] $products
 * @method Product|\October\Rain\Database\Relations\HasMany products()
 *
 * Properties for Shopaholic
 * @property \October\Rain\Database\Collection|\Lovata\PropertiesShopaholic\Models\Property[] $product_property
 * @method \Lovata\PropertiesShopaholic\Models\Property|\October\Rain\Database\Relations\BelongsToMany product_property()
 *
 * @property \October\Rain\Database\Collection|\Lovata\PropertiesShopaholic\Models\Property[] $offer_property
 * @method \Lovata\PropertiesShopaholic\Models\Property|\October\Rain\Database\Relations\BelongsToMany offer_property()
 *
 * Scope list
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
    use CustomValidationMessage;
    use DataFileModel;

    public $table = 'lovata_shopaholic_categories';
    
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_categories',
    ];
    
    public $customMessages = [];
    public $attributeNames = [];

    public $slugs = ['slug' => 'name'];
    
    public $attachOne = ['preview_image' => 'System\Models\File'];
    public $attachMany = ['images' => 'System\Models\File'];
    public $belongsToMany = [];
    public $hasMany = ['products' => 'Lovata\Shopaholic\Models\Product'];

    public $fillable = [
        'name',
        'slug',
        'active',
        'code',
        'external_id',
        'preview_text',
        'description',
    ];

    public $dates = ['created_at', 'updated_at'];
    public $casts = [];

    /**
     * Category constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $iPreviewTextMaxLength = (int) Settings::getValue('category_preview_limit_max');
        if($iPreviewTextMaxLength > 0) {
            $this->rules['preview_text'] = 'max:'.$iPreviewTextMaxLength;
        }

        $this->setCustomMessage(ToolboxPlugin::NAME, ['required', 'unique', 'max.string']);
        $this->setCustomAttributeName(ToolboxPlugin::NAME, ['name', 'slug', 'preview_text']);
        parent::__construct($attributes);
    }

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