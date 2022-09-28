<?php namespace Lovata\Shopaholic\Classes\Api\Mutation;

use Lang;
use Illuminate\Support\Arr;

use GraphQL\Type\Definition\Type;

use Lovata\Shopaholic\Classes\Api\Type\Input\CurrencySwitchActiveInputType;
use Lovata\Shopaholic\Classes\Api\Type\Payload\CurrencySwitchActivePayloadType;
use Lovata\Shopaholic\Classes\Item\CurrencyItem;
use Lovata\Toolbox\Classes\Api\Mutation\AbstractMutationType;
use Lovata\Shopaholic\Classes\Helper\CurrencyHelper;

/**
 * Class CurrencySwitchActiveMutationType
 * @package Lovata\Shopaholic\Classes\Api\Mutation
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class CurrencySwitchActiveMutationType extends AbstractMutationType
{
    const TYPE_ALIAS = 'currencySwitchActive';

    /** @var CurrencySwitchActiveMutationType */
    protected static $instance;

    /** @var string|null */
    protected $sCurrencyCode = null;

    /** @var CurrencyItem|null */
    protected $obActiveCurrensy = null;

    /**
     * Get type object
     * @return \GraphQL\Type\Definition\InputObjectType|\GraphQL\Type\Definition\ObjectType|null
     * @throws \GraphQL\Error\Error
     */
    public function getTypeObject()
    {
        return $this->getRelationType(CurrencySwitchActivePayloadType::TYPE_ALIAS);
    }

    /**
     * Init argument list
     * @return void
     */
    protected function initArgumentList()
    {
        $this->sCurrencyCode = Arr::get($this->arArgumentList, 'input.currencyCode');
    }

    /**
     * Mutation
     */
    protected function mutation(): bool
    {

        CurrencyHelper::instance()->switchActive($this->sCurrencyCode);

        return true;
    }

    /**
     * After mutation logic
     * @return void
     */
    protected function afterMutation()
    {
        parent::afterMutation();
        $this->obActiveCurrensy = CurrencyHelper::instance()->getActive();
    }

    /**
     * Get result
     * @return array
     */
    protected function result(): array
    {
        $arResult = [
            'record'   => $this->obActiveCurrensy,
            'recordId' => $this->obActiveCurrensy->id,
            'status' => $this->bStatus,
        ];

        return $arResult;
    }

    /**
     * Get arguments
     * @return array[]|null
     * @throws \GraphQL\Error\Error
     */
    protected function getArguments(): ?array
    {
        $arArgumentList = [
            'input' => [
                'type'        => Type::nonNull($this->getRelationType(CurrencySwitchActiveInputType::TYPE_ALIAS)),
                'description' => Lang::get('lovata.shopaholic::lang.api_field_description.currency_switch_active_input'),
            ]
        ];

        return $arArgumentList;
    }

    /**
     * Get description
     * @return string
     */
    protected function getDescription(): string
    {
        return Lang::get('lovata.shopaholic::lang.api_mutation_type_description.currency_switch_active');
    }
}
