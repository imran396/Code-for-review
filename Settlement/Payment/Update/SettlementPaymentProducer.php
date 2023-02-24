<?php
/**
 * SAM-9960: Check Printing for Settlements: Payment List management at the "Settlement Edit" page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Payment\Update;

use Payment;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Payment\Update\Internal\DataProviderCreateTrait;
use Sam\Storage\WriteRepository\Entity\Payment\PaymentWriteRepositoryAwareTrait;

/**
 * Class SettlementPaymentProducer
 * @package Sam\Settlement\Payment\Update
 */
class SettlementPaymentProducer extends CustomizableClass
{
    use DataProviderCreateTrait;
    use EntityFactoryCreateTrait;
    use PaymentWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function produce(SettlementPayment $settlementPayment, int $editorUserId): Payment
    {
        $payment = $this->loadPaymentOrCreate($settlementPayment);
        $payment->PaymentMethodId = $settlementPayment->paymentMethodId;
        $payment->Amount = $settlementPayment->amount;
        $payment->Note = $settlementPayment->note;
        $this->getPaymentWriteRepository()->saveWithModifier($payment, $editorUserId);
        return $payment;
    }

    protected function loadPaymentOrCreate(SettlementPayment $settlementPayment): Payment
    {
        if ($settlementPayment->paymentId) {
            $payment = $this->createDataProvider()->loadSettlementPayment($settlementPayment->paymentId);
            if (
                !$payment
                || $payment->TranType !== Constants\Payment::TT_SETTLEMENT
                || $payment->TranId !== $settlementPayment->settlementId
            ) {
                throw new \Exception("Payment with ID {$settlementPayment->paymentId} does not match settlement with ID {$settlementPayment->settlementId}");
            }
        } else {
            $payment = $this->createPayment($settlementPayment->settlementId);
        }

        return $payment;
    }

    protected function createPayment(int $settlementId): Payment
    {
        $payment = $this->createEntityFactory()->payment();
        $payment->TranType = Constants\Payment::TT_SETTLEMENT;
        $payment->TranId = $settlementId;
        $payment->AccountId = $this->createDataProvider()->loadSettlementAccountId($settlementId);
        return $payment;
    }
}
