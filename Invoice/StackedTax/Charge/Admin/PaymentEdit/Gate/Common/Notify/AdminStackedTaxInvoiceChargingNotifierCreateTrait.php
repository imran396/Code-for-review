<?php
/**
 *
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Common\Notify;

trait AdminStackedTaxInvoiceChargingNotifierCreateTrait
{
    protected ?AdminStackedTaxInvoiceChargingNotifier $adminStackedTaxInvoiceChargingNotifier = null;

    /**
     * @return AdminStackedTaxInvoiceChargingNotifier
     */
    protected function createAdminStackedTaxInvoiceChargingNotifier(): AdminStackedTaxInvoiceChargingNotifier
    {
        return $this->adminStackedTaxInvoiceChargingNotifier ?: AdminStackedTaxInvoiceChargingNotifier::new();
    }

    /**
     * @param AdminStackedTaxInvoiceChargingNotifier $adminStackedTaxInvoiceChargingNotifier
     * @return $this
     * @internal
     */
    public function setAdminStackedTaxInvoiceChargingNotifier(AdminStackedTaxInvoiceChargingNotifier $adminStackedTaxInvoiceChargingNotifier): static
    {
        $this->adminStackedTaxInvoiceChargingNotifier = $adminStackedTaxInvoiceChargingNotifier;
        return $this;
    }
}
