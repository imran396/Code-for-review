<?php
/**
 * SAM-10616: Supply uniqueness of invoice fields: invoice#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Lock;

/**
 * Trait UniqueInvoiceNoLockerCreateTrait
 * @package Sam\Invoice\Common\Lock
 */
trait UniqueInvoiceNoLockerCreateTrait
{
    protected ?UniqueInvoiceNoLocker $uniqueInvoiceNoLocker = null;

    /**
     * @return UniqueInvoiceNoLocker
     */
    protected function createUniqueInvoiceNoLocker(): UniqueInvoiceNoLocker
    {
        return $this->uniqueInvoiceNoLocker ?: UniqueInvoiceNoLocker::new();
    }

    /**
     * @param UniqueInvoiceNoLocker $uniqueInvoiceNoLocker
     * @return static
     * @internal
     */
    public function setUniqueInvoiceNoLocker(UniqueInvoiceNoLocker $uniqueInvoiceNoLocker): static
    {
        $this->uniqueInvoiceNoLocker = $uniqueInvoiceNoLocker;
        return $this;
    }
}
