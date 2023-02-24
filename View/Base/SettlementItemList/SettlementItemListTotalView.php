<?php
/**
 * SAM-4364: Settlement item list loading optimization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base\SettlementItemList;

use Payment;
use Sam\Billing\Payment\Render\PaymentRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Settings\SettingsManager;
use Sam\Settlement\AdditionalCharge\SettlementAdditionalChargeManagerAwareTrait;
use Sam\Settlement\Calculate\SettlementCalculatorAwareTrait;
use Sam\Settlement\Payment\Load\SettlementPaymentLoaderCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use SettlementAdditional;

/**
 * Class SettlementItemListTotalView
 * @package Sam\View\Base\SettlementItemList
 */
class SettlementItemListTotalView extends CustomizableClass
{
    use NumberFormatterAwareTrait;
    use OptionalsTrait;
    use PaymentRendererAwareTrait;
    use SettlementAdditionalChargeManagerAwareTrait;
    use SettlementCalculatorAwareTrait;
    use SettlementPaymentLoaderCreateTrait;

    public const OP_CHARGE_CONSIGNOR_COMMISSION = Constants\Setting::CHARGE_CONSIGNOR_COMMISSION;
    public const OP_SETTLEMENT_UNPAID_LOTS = Constants\Setting::SETTLEMENT_UNPAID_LOTS;

    protected int $settlementId;
    protected int $accountId;

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
     * @param int $accountId
     * @param array $optionals
     * @return static
     */
    public function construct(int $settlementId, int $accountId, array $optionals = []): static
    {
        $this->settlementId = $settlementId;
        $this->accountId = $accountId;
        $this->getNumberFormatter()->constructForSettlement($accountId);
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @return array Empty if settlement unpaid lots separation is not applicable
     */
    public function makeUnpaidAmountFormatted(): array
    {
        $amountViewFormatted = '';
        $amountRealFormatted = '';
        if ($this->fetchOptional(self::OP_SETTLEMENT_UNPAID_LOTS, [$this->accountId])) {
            $unpaidAmount = $this->getSettlementCalculator()->calcUnpaidLots($this->settlementId) ?? 0.;
            $amountViewFormatted = $this->getNumberFormatter()->formatMoney($unpaidAmount);
            $amountRealFormatted = $this->getNumberFormatter()->formatMoneyDetail($unpaidAmount);
        }
        return [$amountViewFormatted, $amountRealFormatted];
    }

    /**
     * @return array Empty if settlement unpaid lots separation is not applicable
     */
    public function makePaidAmountFormatted(): array
    {
        $amountViewFormatted = '';
        $amountRealFormatted = '';
        if ($this->fetchOptional(self::OP_SETTLEMENT_UNPAID_LOTS, [$this->accountId])) {
            $paidAmount = $this->getSettlementCalculator()->calcPaidLots($this->settlementId) ?? 0.;
            $amountViewFormatted = $this->getNumberFormatter()->formatMoney($paidAmount);
            $amountRealFormatted = $this->getNumberFormatter()->formatMoneyDetail($paidAmount);
        }
        return [$amountViewFormatted, $amountRealFormatted];
    }

    /**
     * @return array Empty if settlement unpaid lots separation is applicable and should be used instead of subtotal
     */
    public function makeSubtotalAmountFormatted(): array
    {
        $amountViewFormatted = '';
        $amountRealFormatted = '';
        if (!$this->fetchOptional(self::OP_SETTLEMENT_UNPAID_LOTS, [$this->accountId])) {
            $subtotalAmount = $this->fetchOptional(self::OP_CHARGE_CONSIGNOR_COMMISSION, [$this->accountId])
                ? $this->getSettlementCalculator()->calcTotalCommissionWithTax($this->settlementId)
                : $this->getSettlementCalculator()->calcTotal($this->settlementId);
            $amountViewFormatted = $this->getNumberFormatter()->formatMoney($subtotalAmount);
            $amountRealFormatted = $this->getNumberFormatter()->formatMoneyDetail($subtotalAmount);
        }
        return [$amountViewFormatted, $amountRealFormatted];
    }

    /**
     * @param float|null $tax
     * @return array Empty if tax is absent
     */
    public function formatSettlementTax(?float $tax): array
    {
        $taxViewFormatted = '';
        $taxRealFormatted = '';
        if (Floating::gt($tax, 0)) {
            $taxViewFormatted = $this->getNumberFormatter()->formatMoney($tax);
            $taxRealFormatted = $this->getNumberFormatter()->formatMoneyDetail($tax);
        }
        return [$taxViewFormatted, $taxRealFormatted];
    }

    /**
     * @return array
     */
    public function makePaymentList(): array
    {
        $payments = $this->createSettlementPaymentLoader()->loadBySettlementId($this->settlementId, [], true);
        $list = array_map(
            function (Payment $payment) {
                $langPaymentMethodName = $this->getPaymentRenderer()->makePaymentMethodTranslated($payment->PaymentMethodId);
                $paymentAmountFormatted = $this->getNumberFormatter()->formatMoney($payment->Amount);
                return [
                    'label' => $langPaymentMethodName,
                    'amountFormatted' => $paymentAmountFormatted,
                    'note' => ee($payment->Note)
                ];
            },
            $payments
        );
        return $list;
    }

    /**
     * @return array
     */
    public function makeExtraChargeList(): array
    {
        $charges = $this->getSettlementAdditionalChargeManager()->loadBySettlementId($this->settlementId, true);
        $list = array_map(
            function (SettlementAdditional $charge) {
                return [
                    'name' => ee($charge->Name),
                    'amountFormatted' => $this->getNumberFormatter()->formatMoney($charge->Amount)
                ];
            },
            $charges
        );
        if (count($list) === 0) {
            $list[] = [
                'name' => '',
                'amountFormatted' => $this->getNumberFormatter()->formatMoney(0)
            ];
        }
        return $list;
    }

    /**
     * @return array
     */
    public function makeTotalDueFormatted(): array
    {
        $totalDue = $this->getSettlementCalculator()->calcTotalDue($this->settlementId);
        $totalDueViewFormatted = $this->getNumberFormatter()->formatMoney($totalDue);
        $totalDueRealFormatted = $this->getNumberFormatter()->formatMoneyDetail($totalDue);
        return [$totalDueViewFormatted, $totalDueRealFormatted];
    }

    /**
     * @return string
     */
    public function makeBalanceDueFormatted(): string
    {
        $balanceDue = $this->getSettlementCalculator()->calcRoundedBalanceDue($this->settlementId);
        return $this->getNumberFormatter()->formatMoney($balanceDue);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_CHARGE_CONSIGNOR_COMMISSION] = $optionals[self::OP_CHARGE_CONSIGNOR_COMMISSION]
            ?? static function (int $accountId) {
                return SettingsManager::new()->get(Constants\Setting::CHARGE_CONSIGNOR_COMMISSION, $accountId);
            };
        $optionals[self::OP_SETTLEMENT_UNPAID_LOTS] = $optionals[self::OP_SETTLEMENT_UNPAID_LOTS]
            ?? static function (int $accountId) {
                return SettingsManager::new()->get(Constants\Setting::SETTLEMENT_UNPAID_LOTS, $accountId);
            };
        $this->setOptionals($optionals);
    }
}
