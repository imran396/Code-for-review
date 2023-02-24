<?php
/**
 * SAM-6499: Refactor Settlement Calculator module (2020 year)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Settlement\Calculate\Internal\Load\GetSettlementTotals;


use Sam\Core\Service\CustomizableClass;

/**
 * Class GetSettlementTotalsQuery
 * @package Sam\Settlement\Calculate\Internal\Load\GetSettlementTotal
 * @internal
 */
class GetSettlementTotalsQuery extends CustomizableClass
{
    protected int $settlementId;
    protected float $consignorTax;
    protected bool $consignorTaxHpInclusive = false;
    protected bool $consignorTaxHpExclusive = false;
    protected bool $consignorTaxCommissionInclusive = false;
    protected bool $consignorTaxServices = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $settlementId
     * @param float|null $consignorTax null - may be undefined value in s.consignor_tax (the same as 0%)
     * @return $this
     */
    public function construct(int $settlementId, ?float $consignorTax): static
    {
        $this->settlementId = $settlementId;
        $this->consignorTax = (float)$consignorTax;
        return $this;
    }

    /**
     * @return int
     */
    public function getSettlementId(): int
    {
        return $this->settlementId;
    }

    /**
     * @return float
     */
    public function getConsignorTax(): float
    {
        return $this->consignorTax;
    }

    /**
     * @return bool
     */
    public function isConsignorTaxHpInclusive(): bool
    {
        return $this->consignorTaxHpInclusive;
    }

    /**
     * @param bool $consignorTaxHpInclusive
     * @return static
     */
    public function enableConsignorTaxHpInclusive(bool $consignorTaxHpInclusive): static
    {
        $this->consignorTaxHpInclusive = $consignorTaxHpInclusive;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConsignorTaxHpExclusive(): bool
    {
        return $this->consignorTaxHpExclusive;
    }

    /**
     * @param bool $consignorTaxHpExclusive
     * @return static
     */
    public function enableConsignorTaxHpExclusive(bool $consignorTaxHpExclusive): static
    {
        $this->consignorTaxHpExclusive = $consignorTaxHpExclusive;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConsignorTaxCommissionInclusive(): bool
    {
        return $this->consignorTaxCommissionInclusive;
    }

    /**
     * @param bool $consignorTaxCommissionInclusive
     * @return static
     */
    public function enableConsignorTaxCommissionInclusive(bool $consignorTaxCommissionInclusive): static
    {
        $this->consignorTaxCommissionInclusive = $consignorTaxCommissionInclusive;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConsignorTaxServices(): bool
    {
        return $this->consignorTaxServices;
    }

    /**
     * @param bool $consignorTaxServices
     * @return static
     */
    public function enableConsignorTaxServices(bool $consignorTaxServices): static
    {
        $this->consignorTaxServices = $consignorTaxServices;
        return $this;
    }
}
