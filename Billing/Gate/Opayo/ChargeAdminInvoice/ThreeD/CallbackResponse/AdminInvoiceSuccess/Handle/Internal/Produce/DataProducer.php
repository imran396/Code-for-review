<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Sept 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceSuccess\Handle\Internal\Produce;

use DateTime;
use Email_Template;
use Invoice;
use Payment;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Charge\Common\Total\InvoiceTotalsUpdater;
use Sam\Invoice\Common\Payment\InvoicePaymentManager;


class DataProducer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function addInvoicePayment(
        int $invoiceId,
        ?float $amount,
        int $editorUserId,
        string $note,
        DateTime $currentDate,
        int $creditCardId
    ): Payment {
        return InvoicePaymentManager::new()->add(
            $invoiceId,
            Constants\Payment::PM_CC,
            $amount,
            $editorUserId,
            $note,
            $currentDate,
            null,
            $creditCardId
        );
    }

    public function addEmailToActionQueue(Invoice $invoice, int $editorUserId): bool
    {
        $emailManager = Email_Template::new()->construct(
            $invoice->AccountId,
            Constants\EmailKey::PAYMENT_CONF,
            $editorUserId,
            [$invoice]
        );
        $wasAdded = $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);
        return $wasAdded;
    }

    public function recalculateTotalsAndAssign(Invoice $invoice, bool $isReadOnlyDb = false): Invoice
    {
        return InvoiceTotalsUpdater::new()->calcAndAssign($invoice, $isReadOnlyDb);
    }
}
