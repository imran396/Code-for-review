<?php
/**
 * SAM-6648:   Refactor \PdfPrintInvoice and move it away from \Invoice_PdfExportInvoice to \Sam namespace
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Pdf;

use Invoice;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoader;

class LegacyInvoicePdfRenderer extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const INVOICE_NOT_FOUND = 1;
    public const INVOICE_DELETED = 2;

    protected readonly ?Invoice $invoice;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct($invoiceId): static
    {
        $invoice = InvoiceLoader::new()->load($invoiceId, true);
        $this->invoice = $invoice;
        $this->getResultStatusCollector()->construct(
            [
                self::INVOICE_NOT_FOUND => 'Invoice not found',
                self::INVOICE_DELETED => 'Invoice has been deleted.',
            ]
        );
        return $this;
    }

    public function renderOutput(string $mode, int $viewLanguageId): void
    {
        $pdfGenerator = new LegacyInvoicePdfGenerator($this->invoice, $viewLanguageId);
        $pdfGenerator->AddPage();
        //$pdf->ImprovedTable($data);
        $pdfGenerator->itemGrid();
        $pdfGenerator->summaryRows();
        $pdfGenerator->summaryNote();

        if ($mode === "F") {
            $pdfGenerator->Output(path()->uploadAuctionImage() . '/invoice-' . $this->invoice->Id . '.pdf', $mode);
        } else {
            $pdfGenerator->Output('invoice-' . $this->invoice->Id . '.pdf', 'I');
        }
    }

    public function validate(): bool
    {
        if (!$this->invoice) {
            $this->getResultStatusCollector()->addError(self::INVOICE_NOT_FOUND);
            return false;
        }
        if ($this->invoice->isDeleted()) {
            $this->getResultStatusCollector()->addError(self::INVOICE_DELETED);
            return false;
        }
        return true;
    }

    public function errorMessages(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }
}
