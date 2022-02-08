<?php

namespace Lovata\Shopaholic\Models;

use Model;
use Lovata\Toolbox\Traits\Helpers\TraitCached;
use Winter\Storm\Database\Traits\Sluggable;

/**
 * Class ProductSlug
 * @package Lovata\Shopaholic\Models
 * @author  Fon E. Noel NFEBE, fenn25.fn@gmail.com
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 *
 * @property                                                                                               $id
 * @property string                                                                                        $source
 * @property string                                                                                        $slug
 * @property int                                                                                           $product_id
 * @property \October\Rain\Argon\Argon                                                                     $created_at
 * @property \October\Rain\Argon\Argon                                                                     $updated_at
 * @property \October\Rain\Argon\Argon                                                                     $deleted_at
 *
 *
 * Relations
 * @property \Lovata\Shopaholic\Models\Product                                                             $product
 * @method \October\Rain\Database\Relations\BelongsTo|Product product()
 *
 */
class ProductSlug extends Model
{
    use TraitCached;
    use Sluggable;

    public $table = 'lovata_shopaholic_product_slugs';

    public $implement = [
        '@RainLab.Translate.Behaviors.TranslatableModel',
    ];

    public $translatable = ['source'];

    public $rules = ['source' => 'required'];

    public $attributeNames = [
        'source' => 'lovata.toolbox::lang.field.slug_source',
    ];
    public $slugs = [
        'slug' => 'source',
    ];

    public $attachOne = [];
    public $attachMany = [];
    public $belongsTo = [
        'product'          => [Product::class]
    ];
    public $morphMany = [];
    public $morphOne = [];
    public $belongsToMany = [];

    public $fillable = [
        'source',
        'slug',
        'product_id',
    ];

    public $cached = [
        'source',
        'slug',
        'product_id',
    ];

    public $dates = ['created_at', 'updated_at', 'deleted_at'];
    public $visible = [];
    public $hidden = [];


    /**
     * Before validate event handler
     */
    public function beforeValidate()
    {
        if (empty($this->slug)) {
            $this->slugAttributes();
        }
    }


}
