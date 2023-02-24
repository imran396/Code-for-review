<?php
/**
 * SAM-10904: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract User CC Info updating after charge operation
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Common\CcInfo\Update;

/**
 * Trait InvoiceUserCcInfoUpdaterCreateTrait
 * @package
 */
trait InvoiceUserCcInfoUpdaterCreateTrait
{
    protected ?InvoiceUserCcInfoUpdater $invoiceUserCcInfoUpdater = null;

    /**
     * @return InvoiceUserCcInfoUpdater
     */
    protected function createInvoiceUserCcInfoUpdater(): InvoiceUserCcInfoUpdater
    {
        return $this->invoiceUserCcInfoUpdater ?: InvoiceUserCcInfoUpdater::new();
    }

    /**
     * @param InvoiceUserCcInfoUpdater $invoiceUserCcInfoUpdater
     * @return $this
     * @internal
     */
    public function setInvoiceUserCcInfoUpdater(InvoiceUserCcInfoUpdater $invoiceUserCcInfoUpdater): static
    {
        $this->invoiceUserCcInfoUpdater = $invoiceUserCcInfoUpdater;
        return $this;
    }
}
