<?php namespace Lovata\Shopaholic\Models;

use Model;

use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Scope\SlugField;

use October\Rain\Database\Traits\Sluggable;
use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\Sortable;

use Lovata\Toolbox\Traits\Helpers\TraitCached;

/**
 * Class Brand
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
 * Relations
 * @property \System\Models\File $preview_image
 * @property \October\Rain\Database\Collection|\System\Models\File[] $images
 *
 * @property \October\Rain\Database\Collection|Product[] $product
 * @method \October\Rain\Database\Relations\HasMany|Product product()
 *
 * Search for Shopaholic, Sphinx for Shopaholic
 * @property string $search_synonym
 * @property string $search_content
 */
class Brand extends Model
{
    use Validation;
    use Sortable;
    use Sluggable;
    use ActiveField;
    use NameField;
    use CodeField;
    use SlugField;
    use ExternalIDField;
    use TraitCached;

    public $table = 'lovata_shopaholic_brands';

    public $implement = [
        '@RainLab.Translate.Behaviors.TranslatableModel',
    ];

    public $translatable = ['name', 'preview_text', 'description'];

    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_brands',
    ];

    public $attributeNames = [
        'name' => 'lovata.toolbox::lang.field.name',
        'slug' => 'lovata.toolbox::lang.field.slug',
    ];

    public $slugs = ['slug' => 'name'];

    public $attachOne = ['preview_image' => 'System\Models\File'];
    public $attachMany = ['images' => 'System\Models\File'];
    public $hasMany = ['product' => Product::class];

    public $dates = ['created_at', 'updated_at'];

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
        'preview_image',
        'description',
        'images',
    ];
}
