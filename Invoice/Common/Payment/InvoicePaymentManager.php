<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           22.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Common\Payment;

use DateTime;
use Payment;
use RuntimeException;
use Sam\Billing\Payment\Load\PaymentLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\Payment\PaymentReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\Payment\PaymentWriteRepositoryAwareTrait;

/**
 * Class InvoicePaymentManager
 * @package Sam\Invoice\Common\Payment
 */
class InvoicePaymentManager extends CustomizableClass
{
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use InvoiceLoaderAwareTrait;
    use PaymentLoaderAwareTrait;
    use PaymentReadRepositoryCreateTrait;
    use PaymentWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Add payment to payment manager
     *
     * @param int $invoiceId
     * @param int|null $paymentMethodId
     * @param float|null $amount
     * @param int $editorUserId
     * @param string|null $note
     * @param DateTime|null $date
     * @param string|null $txnId
     * @param int|null $creditCardId
     * @return Payment
     */
    public function add(
        int $invoiceId,
        ?int $paymentMethodId,
        ?float $amount,
        int $editorUserId,
        ?string $note = null,
        ?DateTime $date = null,
        ?string $txnId = null,
        ?int $creditCardId = null
    ): Payment {
        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            throw CouldNotFindInvoice::withId($invoiceId);
        }
        return $this->addFull(
            $invoiceId,
            $invoice->AccountId,
            $paymentMethodId,
            $amount,
            $editorUserId,
            $note,
            $date,
            $txnId,
            $creditCardId
        );
    }

    public function addFull(
        int $invoiceId,
        int $invoiceAccountId,
        ?int $paymentMethodId,
        ?float $amount,
        int $editorUserId,
        ?string $note = null,
        ?DateTime $date = null,
        ?string $txnId = null,
        ?int $creditCardId = null
    ): Payment {
        $payment = $this->createEntityFactory()->payment();
        $payment->AccountId = $invoiceAccountId;
        $payment->Amount = $amount;
        $payment->CreditCardId = $creditCardId;
        $payment->Note = trim((string)$note);
        $payment->PaidOn = $date ?: null;
        $payment->PaymentMethodId = $paymentMethodId;
        $payment->TranId = $invoiceId;
        $payment->TranType = Constants\Payment::TT_INVOICE;
        $payment->TxnId = $txnId ?: '';
        $this->getPaymentWriteRepository()->saveWithModifier($payment, $editorUserId);
        return $payment;
    }

    public function updateFull(
        int $paymentId,
        int $invoiceId,
        int $invoiceAccountId,
        ?int $paymentMethodId,
        ?float $amount,
        int $editorUserId,
        ?string $note = null,
        ?DateTime $date = null,
        ?string $txnId = null,
        ?int $creditCardId = null
    ): Payment {
        $payment = $this->getPaymentLoader()->load($paymentId);
        if (
            !$payment
            || $payment->TranType !== Constants\Payment::TT_INVOICE
            || $payment->TranId !== $invoiceId
            || $payment->AccountId !== $invoiceAccountId
        ) {
            throw new RuntimeException('Invalid payment id' . composeSuffix(['p' => $paymentId, 'i' => $invoiceId, 'acc' => $invoiceAccountId]));
        }

        $payment->Amount = $amount;
        $payment->CreditCardId = $creditCardId;
        $payment->Note = trim((string)$note);
        $payment->PaidOn = $date ?: null;
        $payment->PaymentMethodId = $paymentMethodId;
        $payment->TxnId = $txnId ?: '';
        $this->getPaymentWriteRepository()->saveWithModifier($payment, $editorUserId);
        return $payment;
    }

    /**
     * @param int $tranId
     * @param array $skipIds
     * @param int $editorUserId
     */
    public function deleteForInvoice(int $tranId, array $skipIds, int $editorUserId): void
    {
        $payments = $this->createPaymentReadRepository()
            ->filterActive(true)
            ->filterTranId($tranId)
            ->filterTranType(Constants\Payment::TT_INVOICE)
            ->skipId($skipIds)
            ->loadEntities();

        foreach ($payments as $payment) {
            $payment->toDeleted();
            $this->getPaymentWriteRepository()->saveWithModifier($payment, $editorUserId);
        }
    }

    /**
     * @param int $tranId
     * @param bool $isReadOnlyDb
     * @return Payment[]
     */
    public function loadForInvoice(int $tranId, bool $isReadOnlyDb = false): array
    {
        $payment = $this->createPaymentReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterTranId($tranId)
            ->filterTranType(Constants\Payment::TT_INVOICE)
            ->loadEntities();
        return $payment;
    }
}
