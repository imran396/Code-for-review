<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\CreditCardSurcharge;

class CreditCardSurchargeReadRepository extends AbstractCreditCardSurchargeReadRepository
{
    protected array $joins = [
        'credit_card' => 'JOIN credit_card cc ON ccs.credit_card_id = cc.id',
    ];

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Left join `user` table
     * @return static
     */
    public function joinCreditCard(): static
    {
        $this->join('credit_card');
        return $this;
    }

    /**
     * Define filtering by credit_card.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinCreditCardFilterActive(bool|array|null $active): static
    {
        $this->joinCreditCard();
        $this->filterArray('cc.active', $active);
        return $this;
    }
}
