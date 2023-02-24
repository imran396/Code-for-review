<?php
/**
 *
 * SAM-11778: Refactor Invoice Notifier for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Notify\Multiple;

/**
 * Trait MultipleInvoiceNotifierCreateTrait
 * @package Sam\Invoice\Common\Notify\Multiple
 */
trait MultipleInvoiceNotifierCreateTrait
{
    protected ?MultipleInvoiceNotifier $multipleInvoiceNotifier = null;

    /**
     * @return MultipleInvoiceNotifier
     */
    protected function createMultipleInvoiceNotifier(): MultipleInvoiceNotifier
    {
        return $this->multipleInvoiceNotifier ?: MultipleInvoiceNotifier::new();
    }

    /**
     * @param MultipleInvoiceNotifier $multipleInvoiceNotifier
     * @return $this
     * @internal
     */
    public function setMultipleInvoiceNotifier(MultipleInvoiceNotifier $multipleInvoiceNotifier): self
    {
        $this->multipleInvoiceNotifier = $multipleInvoiceNotifier;
        return $this;
    }
}
