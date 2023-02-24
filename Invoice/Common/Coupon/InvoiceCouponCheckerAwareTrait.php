<?php
/**
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
 * Trait InvoiceCouponCheckerAwareTrait
 * @package Sam\Invoice\Common\Coupon
 */
trait InvoiceCouponCheckerAwareTrait
{
    /**
     * @var InvoiceCouponChecker|null
     */
    protected ?InvoiceCouponChecker $invoiceCouponChecker = null;

    /**
     * @return InvoiceCouponChecker
     */
    protected function getInvoiceCouponChecker(): InvoiceCouponChecker
    {
        if ($this->invoiceCouponChecker === null) {
            $this->invoiceCouponChecker = InvoiceCouponChecker::new();
        }
        return $this->invoiceCouponChecker;
    }

    /**
     * @param InvoiceCouponChecker $invoiceCouponChecker
     * @return static
     * @internal
     */
    public function setInvoiceCouponChecker(InvoiceCouponChecker $invoiceCouponChecker): static
    {
        $this->invoiceCouponChecker = $invoiceCouponChecker;
        return $this;
    }
}
