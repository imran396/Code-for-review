<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           26.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Common\Coupon;

/**
 * Trait InvoiceCouponApplierAwareTrait
 * @package Sam\Invoice\Common\Coupon
 */
trait InvoiceCouponApplierAwareTrait
{
    /**
     * @var InvoiceCouponApplier|null
     */
    protected ?InvoiceCouponApplier $invoiceCouponApplier = null;

    /**
     * @return InvoiceCouponApplier
     */
    protected function getInvoiceCouponApplier(): InvoiceCouponApplier
    {
        if ($this->invoiceCouponApplier === null) {
            $this->invoiceCouponApplier = InvoiceCouponApplier::new();
        }
        return $this->invoiceCouponApplier;
    }

    /**
     * @param InvoiceCouponApplier $invoiceCouponApplier
     * @return static
     * @internal
     */
    public function setInvoiceCouponApplier(InvoiceCouponApplier $invoiceCouponApplier): static
    {
        $this->invoiceCouponApplier = $invoiceCouponApplier;
        return $this;
    }
}
