<?php namespace Kharanenka\Scope;

/**
 * Class ActiveField
 * @package Kharanenka\Scope
 * @author Andrey Kharanenka, kharanenka@gmail.com
 *
 * @property bool $active
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder|$this|ActiveField active()
 * @method static \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder|$this|ActiveField notActive()
 */

trait ActiveField {

    /**
     * Get active elements
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeActive($obQuery) {
        return $obQuery->where('active', true);
    }

    /**
     * Get not active elements
     * @param \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder $obQuery
     * @return \Illuminate\Database\Eloquent\Builder|\October\Rain\Database\Builder;
     */
    public function scopeNotActive($obQuery) {
        return $obQuery->where('active', false);
    }
}