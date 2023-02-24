<?php
/**
 * SAM-11142: Stacked Tax. Invoice Management pages. Merge invoices
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Merge\Save;

use Invoice;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

class StackedTaxInvoiceMergingSaveResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const OK_MERGED = 1;

    /** @var string[] */
    public const SUCCESS_MESSAGES = [
        self::OK_MERGED => 'Invoices has been merged into (new #: %s, old #: %s)',
    ];
    public ?Invoice $mergedInvoice = null;
    public ?array $mergedInvoiceAuctions = null;
    public ?array $mergedInvoiceExtraCharges = null;
    public ?array $mergedInvoiceItems = null;
    public ?array $mergedInvoicePayments = null;
    public ?array $deletedInvoices = null;

    // --- Constructor ---

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        $this->getResultStatusCollector()->construct([], static::SUCCESS_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    /**
     * @param int $code
     */
    public function addSuccess(int $code): void
    {
        $this->getResultStatusCollector()->addSuccess($code);
    }

    // --- Query ---

    /**
     * @param Invoice[] $validInvoices
     * @return string
     */
    public function getSuccessMessage(array $validInvoices): string
    {
        if (!$this->mergedInvoice) {
            return '';
        }
        $successMessage = $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
        return sprintf($successMessage, $this->mergedInvoice->InvoiceNo, $this->getMergedInvoiceList($validInvoices));
    }

    //-- Internal logic

    /**
     * @param Invoice[] $validInvoices
     * @return string
     */
    protected function getMergedInvoiceList(array $validInvoices): string
    {
        $invoiceNos = array_map(
            static function (Invoice $invoice) {
                return $invoice->InvoiceNo;
            },
            $validInvoices
        );
        return implode(', ', $invoiceNos);
    }
}
