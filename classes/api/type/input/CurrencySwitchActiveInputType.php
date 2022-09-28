<?php namespace Lovata\Shopaholic\Classes\Api\Type\Input;

use Lang;
use GraphQL\Type\Definition\Type;
use Lovata\Toolbox\Classes\Api\Type\Input\AbstractInputType;

/**
 * Class CurrencySwitchActiveInputType
 * @package Lovata\Shopaholic\Classes\Api\Type\Input
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class CurrencySwitchActiveInputType extends AbstractInputType
{
    const TYPE_ALIAS = 'CurrencySwitchActiveInput';

    /** @var CurrencySwitchActiveInputType */
    protected static $instance;

    /**
     * Get field list
     * @return array[]
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'currencyCode' => [
                'type'        => Type::nonNull(Type::string()),
                'description' => Lang::get('lovata.shopaholic::lang.api_field_description.currency_code'),
            ],
        ];

        return $arFieldList;
    }

    /**
     * Get description
     * @return string
     */
    protected function getDescription(): string
    {
        return Lang::get('lovata.shopaholic::lang.api_object_type_description.currency_switch_active_input');
    }
}
