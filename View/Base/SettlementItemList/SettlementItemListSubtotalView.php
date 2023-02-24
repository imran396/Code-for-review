<?php
/**
 * SAM-4364: Settlement item list loading optimization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base\SettlementItemList;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Calculate\SettlementCalculatorAwareTrait;
use Sam\Settlement\Load\Dto\SettlementItemDto;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Base\SettlementItemList\Internal\SettlementItemTaxCalculatorCreateTrait;

/**
 * Class SettlementItemListSubtotalView
 * @package Sam\View\Base\SettlementItemList
 */
class SettlementItemListSubtotalView extends CustomizableClass
{
    use NumberFormatterAwareTrait;
    use SettlementCalculatorAwareTrait;
    use SettlementItemTaxCalculatorCreateTrait;

    /**
     * @var int
     */
    protected int $settlementId;
    /**
     * @var SettlementItemDto[]
     */
    protected array $settlementItemDtos;
    /**
     * @var string
     */
    protected string $currencySign;

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
     * @param array $settlementItemDtos
     * @param string $currencySign
     * @param int $accountId
     * @return static
     */
    public function construct(int $settlementId, array $settlementItemDtos, string $currencySign, int $accountId): static
    {
        $this->settlementId = $settlementId;
        $this->settlementItemDtos = $settlementItemDtos;
        $this->currencySign = $currencySign;
        $this->initNumberFormatter($accountId);
        return $this;
    }

    /**
     * @return string
     */
    public function makeHPFormatted(): string
    {
        $hpSubtotal = $this->getSettlementCalculator()->calcTotalHammerPrice($this->settlementId);
        $taxHpSubtotalViewFormatted = $this->getNumberFormatter()->formatMoney($hpSubtotal);
        $taxHpSubtotalRealFormatted = $this->getNumberFormatter()->formatMoneyDetail($hpSubtotal);
        return sprintf(
            '%s<span title="%s%s">%s</span>',
            $this->currencySign,
            $this->currencySign,
            $taxHpSubtotalRealFormatted,
            $taxHpSubtotalViewFormatted
        );
    }

    /**
     * @return string
     */
    public function makeTaxOnHPFormatted(): string
    {
        $settlementTaxCalculator = $this->createSettlementItemTaxCalculator();
        $taxOnHpSubtotal = array_reduce(
            $this->settlementItemDtos,
            static function (float $carry, SettlementItemDto $settlementItemDto) use ($settlementTaxCalculator) {
                return $carry + $settlementTaxCalculator->calcTaxOnHP($settlementItemDto);
            },
            0.
        );
        $taxOnHpSubtotalViewFormatted = $this->getNumberFormatter()->formatMoney($taxOnHpSubtotal);
        $taxOnHpSubtotalRealFormatted = $this->getNumberFormatter()->formatMoneyDetail($taxOnHpSubtotal);
        return sprintf(
            '%s<span title="%s%s">%s</span>',
            $this->currencySign,
            $this->currencySign,
            $taxOnHpSubtotalRealFormatted,
            $taxOnHpSubtotalViewFormatted
        );
    }

    /**
     * @return string
     */
    public function makeTaxOnCommissionFormatted(): string
    {
        $settlementTaxCalculator = $this->createSettlementItemTaxCalculator();
        $taxOnHpCommission = array_reduce(
            $this->settlementItemDtos,
            static function (float $carry, SettlementItemDto $settlementItemDto) use ($settlementTaxCalculator) {
                return $carry + $settlementTaxCalculator->calcTaxOnComm($settlementItemDto);
            },
            0.
        );
        $taxOnHpCommissionViewFormatted = $this->getNumberFormatter()->formatMoney($taxOnHpCommission);
        $taxOnHpCommissionRealFormatted = $this->getNumberFormatter()->formatMoneyDetail($taxOnHpCommission);
        return sprintf(
            '%s<span title="%s%s">%s</span>',
            $this->currencySign,
            $this->currencySign,
            $taxOnHpCommissionRealFormatted,
            $taxOnHpCommissionViewFormatted
        );
    }

    /**
     * @return string
     */
    public function makeFeeFormatted(): string
    {
        $feeSubtotal = $this->getSettlementCalculator()->calcTotalFee($this->settlementId);
        $feeSubtotalViewFormatted = $this->getNumberFormatter()->formatMoney($feeSubtotal);
        $feeSubtotalRealFormatted = $this->getNumberFormatter()->formatMoneyDetail($feeSubtotal);
        return sprintf(
            '%s<span title="%s%s">%s</span>',
            $this->currencySign,
            $this->currencySign,
            $feeSubtotalRealFormatted,
            $feeSubtotalViewFormatted
        );
    }

    /**
     * @return string
     */
    public function makeCommissionFormatted(): string
    {
        $commissionSubtotal = $this->getSettlementCalculator()->calcTotalCommission($this->settlementId);
        $commissionSubtotalViewFormatted = $this->getNumberFormatter()->formatMoney($commissionSubtotal);
        $commissionSubtotalRealFormatted = $this->getNumberFormatter()->formatMoneyDetail($commissionSubtotal);
        return sprintf(
            '%s<span title="%s%s">%s</span>',
            $this->currencySign,
            $this->currencySign,
            $commissionSubtotalRealFormatted,
            $commissionSubtotalViewFormatted
        );
    }

    /**
     * @return string
     */
    public function makeSubtotalFormatted(): string
    {
        $subtotal = $this->getSettlementCalculator()->calcTotal($this->settlementId);
        $subtotalViewFormatted = $this->getNumberFormatter()->formatMoney($subtotal);
        $subtotalRealFormatted = $this->getNumberFormatter()->formatMoneyDetail($subtotal);
        return sprintf(
            '%s<span title="%s%s">%s</span>',
            $this->currencySign,
            $this->currencySign,
            $subtotalRealFormatted,
            $subtotalViewFormatted
        );
    }

    /**
     * @param int $accountId
     */
    protected function initNumberFormatter(int $accountId): void
    {
        $this->getNumberFormatter()->constructForSettlement($accountId);
    }
}
