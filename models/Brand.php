<?php namespace Lovata\Shopaholic\Models;

use Model;

use Kharanenka\Helper\CustomValidationMessage;
use Kharanenka\Helper\DataFileModel;
use Kharanenka\Scope\ActiveField;
use Kharanenka\Scope\CodeField;
use Kharanenka\Scope\ExternalIDField;
use Kharanenka\Scope\NameField;
use Kharanenka\Scope\SlugField;

use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\Sortable;

use Lovata\Toolbox\Plugin as ToolboxPlugin;

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
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * Relations
 * @property \System\Models\File $preview_image
 * @property \October\Rain\Database\Collection|\System\Models\File[] $images
 *
 * @property \October\Rain\Database\Collection|Product[] $products
 * @method $this|\October\Rain\Database\Relations\HasMany products()
 *
 */
class Brand extends Model
{
    use Validation;
    use Sortable;
    use ActiveField;
    use NameField;
    use CodeField;
    use SlugField;
    use ExternalIDField;
    use CustomValidationMessage;
    use DataFileModel;

    public $table = 'lovata_shopaholic_brands';
    
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:lovata_shopaholic_brands',
    ];
    
    public $customMessages = [];
    public $attributeNames = [];

    public $attachOne = ['preview_image' => 'System\Models\File'];
    public $attachMany = ['images' => 'System\Models\File'];
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

    /**
     * Brand constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setCustomMessage(ToolboxPlugin::NAME, ['required', 'unique']);
        $this->setCustomAttributeName(ToolboxPlugin::NAME, ['name', 'slug']);
        parent::__construct($attributes);
    }
}