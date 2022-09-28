<?php namespace Lovata\Shopaholic\Classes\Api\Type\Payload;

use Lang;
use Lovata\Shopaholic\Classes\Api\Item\CurrencyItemType;
use Lovata\Toolbox\Classes\Api\Type\Payload\AbstractMutationPayloadType;

/**
 * Class CurrencySwitchActivePayloadType
 * @package Lovata\Shopaholic\Classes\Api\Type\Payload
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class CurrencySwitchActivePayloadType extends AbstractMutationPayloadType
{
    const TYPE_ALIAS = 'CurrencySwitchActivePayload';

    const RELATED_TYPE_ALIAS = CurrencyItemType::TYPE_ALIAS;

    /** @var CurrencySwitchActivePayloadType */
    protected static $instance;

    protected function getDescription(): string
    {
        return Lang::get('lovata.shopaholic::lang.api_object_type_description.currency_switch_active_payload');
    }
}
