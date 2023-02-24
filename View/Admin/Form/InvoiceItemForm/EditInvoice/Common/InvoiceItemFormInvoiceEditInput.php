<?php
/**
 * SAM-10923: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract invoice General validation and save (#invoice-save-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 05, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Common;

use DateTime;
use Invoice;
use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceItemFormInvoiceEditingInput
 * @package Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Save
 */
class InvoiceItemFormInvoiceEditInput extends CustomizableClass
{
    public Invoice $invoice;
    /** @var InvoiceItemFormInvoiceEditChargeInput[] */
    public array $charges;
    /** @var array ii.id => is released */
    public array $invoiceReleaseStatuses;
    public array $paymentAndChargeMap;  // TODO: decouple
    public array $paymentAndControlMap; // TODO: decouple
    /**
     * @var array{0?: float, 1?: string, 2?: ?DateTime, 3?: int}
     */
    public array $paymentDetails;
    //
    public array $paymentMethods;
    /** @var InvoiceItemFormInvoiceEditPaymentInput[] */
    public array $payments;
    public bool $isCashDiscount;
    public bool $isExcludeInThreshold;
    public int $editorUserId;
    public int $selectedInvoiceStatus;
    public int $systemAccountId;
    public string $internalNote;
    public string $note;
    public string $shippingAmountFormatted;
    public string $shippingNote;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array{0?: float, 1?: string, 2?: ?DateTime, 3?: int} $paymentDetails
     */
    public function construct(
        int $systemAccountId,
        int $editorUserId,
        Invoice $invoice,
        array $paymentDetails,
        int $selectedInvoiceStatus,
        string $note,
        string $internalNote,
        string $shippingAmountFormatted,
        string $shippingNote,
        bool $isCashDiscount,
        bool $isExcludeInThreshold,
        array $invoiceReleaseStatuses,
        array $paymentMethods,
        array $payments,
        array $charges,
        array $paymentAndControlMap, // TODO: decouple
        array $paymentAndChargeMap  // TODO: decouple
    ): static
    {
        $this->paymentDetails = $paymentDetails;
        $this->invoice = $invoice;
        $this->systemAccountId = $systemAccountId;
        $this->editorUserId = $editorUserId;
        $this->selectedInvoiceStatus = $selectedInvoiceStatus;
        $this->note = $note;
        $this->shippingAmountFormatted = $shippingAmountFormatted;
        $this->shippingNote = $shippingNote;
        $this->isCashDiscount = $isCashDiscount;
        $this->isExcludeInThreshold = $isExcludeInThreshold;
        $this->internalNote = $internalNote;
        $this->invoiceReleaseStatuses = $invoiceReleaseStatuses;
        $this->paymentMethods = $paymentMethods;
        $this->payments = $payments;
        $this->charges = $charges;
        $this->paymentAndControlMap = $paymentAndControlMap; // TODO: decouple
        $this->paymentAndChargeMap = $paymentAndChargeMap;  // TODO: decouple
        return $this;
    }
}
