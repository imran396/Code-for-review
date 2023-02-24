<?php
/**
 * SAM-10909: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. General adjustments
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Payment\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\InvoicePaymentMethod\InvoicePaymentMethodReadRepositoryCreateTrait;

/**
 * Class InvoicePaymentMethodExistenceChecker
 * @package Sam\Invoice\Common\Payment\Validate
 */
class InvoicePaymentMethodExistenceChecker extends CustomizableClass
{
    use InvoicePaymentMethodReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Function that checks if there is paypal payment method
     *
     * @param int|null $invoiceId
     * @return bool
     */
    public function isPaypalEnabled(?int $invoiceId): bool
    {
        return $this->createInvoicePaymentMethodReadRepository()
            ->filterInvoiceId($invoiceId)
            ->filterPaymentMethodId(Constants\Payment::IPM_PAYPAL)
            ->filterActive(true)
            ->exist();
    }

    /**
     * Function that checks if there is smartpay payment method
     *
     * @param int|null $invoiceId
     * @return bool
     */
    public function isSmartPayEnabled(?int $invoiceId): bool
    {
        return $this->createInvoicePaymentMethodReadRepository()
            ->filterInvoiceId($invoiceId)
            ->filterPaymentMethodId(Constants\Payment::IPM_SMART_PAY)
            ->filterActive(true)
            ->exist();
    }

    /**
     * Function that checks if there is credit card payment method
     *
     * @param int|null $invoiceId
     * @return bool
     */
    public function isCcEnabled(?int $invoiceId): bool
    {
        return $this->createInvoicePaymentMethodReadRepository()
            ->filterInvoiceId($invoiceId)
            ->filterPaymentMethodId(Constants\Payment::IPM_CC)
            ->filterActive(true)
            ->exist();
    }

    /**
     * Function that checks if there is ach or bank wire payment method
     *
     * @param int|null $invoiceId
     * @return bool
     */
    public function isAchEnabled(?int $invoiceId): bool
    {
        return $this->createInvoicePaymentMethodReadRepository()
            ->filterInvoiceId($invoiceId)
            ->filterPaymentMethodId(Constants\Payment::IPM_ACH)
            ->filterActive(true)
            ->exist();
    }
}
