<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/3/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Currency;

/**
 * Trait InvoiceCurrencyDetectorAwareTrait
 * @package Sam\Invoice\Common\Currency
 */
trait InvoiceCurrencyDetectorAwareTrait
{
    /**
     * @var InvoiceCurrencyDetector|null
     */
    protected ?InvoiceCurrencyDetector $invoiceCurrencyDetector = null;

    /**
     * @return InvoiceCurrencyDetector
     */
    protected function getInvoiceCurrencyDetector(): InvoiceCurrencyDetector
    {
        if ($this->invoiceCurrencyDetector === null) {
            $this->invoiceCurrencyDetector = InvoiceCurrencyDetector::new();
        }
        return $this->invoiceCurrencyDetector;
    }

    /**
     * @param InvoiceCurrencyDetector $invoiceCurrencyDetector
     * @return static
     * @internal
     */
    public function setInvoiceCurrencyDetector(InvoiceCurrencyDetector $invoiceCurrencyDetector): static
    {
        $this->invoiceCurrencyDetector = $invoiceCurrencyDetector;
        return $this;
    }
}
