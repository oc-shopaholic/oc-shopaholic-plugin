<?php namespace Lovata\Shopaholic\Tests\Unit\Models;

include_once __DIR__.'/../../../../toolbox/vendor/autoload.php';
include_once __DIR__.'/../../../../../../tests/PluginTestCase.php';

use PluginTestCase;
use Lovata\Shopaholic\Models\Measure;
use Lovata\Toolbox\Traits\Tests\TestModelValidationNameField;

/**
 * Class MeasureTest
 * @package Lovata\Shopaholic\Tests\Unit\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Measure
 *
 * @mixin \PHPUnit\Framework\Assert
 */
class MeasureTest extends PluginTestCase
{
    use TestModelValidationNameField;

    protected $sModelClass;

    /**
     * MeasureTest constructor.
     */
    public function __construct()
    {
        $this->sModelClass = Measure::class;
        parent::__construct();
    }
}
