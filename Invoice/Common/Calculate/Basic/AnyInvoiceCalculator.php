<?php
/**
 * This service unites calculation logic for the legacy and stacked tax invoices.
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Calculate\Basic;

use Invoice;
use RuntimeException;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Legacy\Calculate\Basic\LegacyInvoiceCalculatorAwareTrait;

/**
 * Class AnyInvoiceCalculator
 * @package Sam\Invoice\Common\Calculate\Basic
 */
class AnyInvoiceCalculator extends CustomizableClass
{
    use LegacyInvoiceCalculatorAwareTrait;
    use InvoiceLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcRoundedBalanceDueByInvoiceId(int $invoiceId, bool $isReadOnlyDb = false): float
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            throw CouldNotFindInvoice::withId($invoiceId);
        }
        return $this->calcRoundedBalanceDue($invoice, $isReadOnlyDb);
    }

    /**
     * @param Invoice $invoice
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcRoundedBalanceDue(Invoice $invoice, bool $isReadOnlyDb = false): float
    {
        if ($invoice->isLegacyTaxDesignation()) {
            return $this->getLegacyInvoiceCalculator()->calcRoundedBalanceDue($invoice->Id, 2, $isReadOnlyDb);
        }

        if ($invoice->isStackedTaxDesignation()) {
            return $invoice->calcRoundedBalanceDue();
        }

        throw new RuntimeException("Cannot calculate balance due, because tax designation of invoice is unknown");
    }

    public function calcGrandTotalByInvoiceId(int $invoiceId, bool $isReadOnlyDb = false): float
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            throw CouldNotFindInvoice::withId($invoiceId);
        }
        return $this->calcGrandTotal($invoice, $isReadOnlyDb);
    }

    public function calcGrandTotal(Invoice $invoice, bool $isReadOnlyDb = false): float
    {
        if ($invoice->isLegacyTaxDesignation()) {
            return $this->getLegacyInvoiceCalculator()->calcGrandTotal($invoice->Id, $isReadOnlyDb);
        }

        if ($invoice->isStackedTaxDesignation()) {
            return $invoice->calcInvoiceTotal(); // TODO: cash discount?
        }

        throw new RuntimeException("Cannot calculate grand total due, because tax designation of invoice is unknown");
    }
}
