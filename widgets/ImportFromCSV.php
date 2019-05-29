<?php namespace Lovata\Shopaholic\Widgets;

use Backend\Classes\ReportWidgetBase;

/**
 * Class ImportFromCSV
 * @package Lovata\Shopaholic\Widgets
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ImportFromCSV extends ReportWidgetBase
{
    /**
     * Render method
     * @return mixed|string
     * @throws \SystemException
     */
    public function render()
    {
        return $this->makePartial('widget');
    }
}
