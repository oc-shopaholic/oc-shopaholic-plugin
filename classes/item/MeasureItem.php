<?php namespace Lovata\Shopaholic\Classes\Item;

use Lovata\Shopaholic\Models\Measure;

use Lovata\Toolbox\Classes\Item\ElementItem;

/**
 * Class MeasureItem
 * @package Lovata\Shopaholic\Classes\Item
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @see     \Lovata\Shopaholic\Tests\Unit\Item\MeasureItemTest
 *
 * @property int    $id
 * @property string $name
 * @property string $code
 */
class MeasureItem extends ElementItem
{
    const MODEL_CLASS = Measure::class;

    /** @var Measure */
    protected $obElement = null;
}
