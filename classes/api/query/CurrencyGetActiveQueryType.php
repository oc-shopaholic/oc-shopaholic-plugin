<?php namespace Lovata\Shopaholic\Classes\Api\Query;

use Lang;
use Lovata\Shopaholic\Classes\Api\Item\CurrencyItemType;
use Lovata\Shopaholic\Classes\Helper\CurrencyHelper;
use Lovata\Toolbox\Classes\Api\Type\AbstractApiType;

/**
 * Class CurrencyGetActiveQueryType
 * @package Lovata\Shopaholic\Classes\Api\Query
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class CurrencyGetActiveQueryType extends AbstractApiType
{
    const TYPE_ALIAS = 'currencyGetActive';

    /** @var CurrencyGetActiveQueryType */
    protected static $instance;

    /**
     * getTypeObject
     * @return \GraphQL\Type\Definition\InputObjectType|\GraphQL\Type\Definition\ObjectType|null
     * @throws \GraphQL\Error\Error
     */
    public function getTypeObject()
    {
        return $this->getRelationType(CurrencyItemType::TYPE_ALIAS);
    }

    /**
     * Get resolve method
     * @return callable|null
     */
    protected function getResolveMethod(): ?callable
    {
        return function () {
            return CurrencyHelper::instance()->getActive();
        };
    }

    /**
     * @inheritDoc
     */
    protected function getFieldList(): array
    {
        return [];
    }

    /**
     * Get description
     * @return string
     */
    protected function getDescription(): string
    {
        return Lang::get('lovata.shopaholic::lang.api_query_type_description.currency_get_active');
    }
}
