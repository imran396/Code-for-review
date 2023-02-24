<?php
/**
 * SAM-5656: One invoice for grouped sales creating multiple invoice for one user
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Chunk\Internal\DetectClosing;

/**
 * Trait InvoiceClosingDetectorCreateTrait
 * @package Sam\Invoice\StackedTax\Generate\Chunk\Internal\DetectClosing
 */
trait InvoiceClosingDetectorCreateTrait
{
    /**
     * @var InvoiceClosingDetector|null
     */
    protected ?InvoiceClosingDetector $invoiceClosingDetector = null;

    /**
     * @return InvoiceClosingDetector
     */
    protected function createInvoiceClosingDetector(): InvoiceClosingDetector
    {
        return $this->invoiceClosingDetector ?: InvoiceClosingDetector::new();
    }

    /**
     * @param InvoiceClosingDetector $invoiceClosingDetector
     * @return $this
     * @internal
     */
    public function setInvoiceClosingDetector(InvoiceClosingDetector $invoiceClosingDetector): static
    {
        $this->invoiceClosingDetector = $invoiceClosingDetector;
        return $this;
    }
}
