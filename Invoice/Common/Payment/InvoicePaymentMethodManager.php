<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           22.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Common\Payment;

use InvoicePaymentMethod;
use Sam\Billing\Gate\Availability\BillingGateAvailabilityCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\InvoicePaymentMethod\InvoicePaymentMethodReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\InvoicePaymentMethod\InvoicePaymentMethodWriteRepositoryAwareTrait;

/**
 * Class InvoicePaymentMethodManager
 * @package Sam\Invoice\Common\Payment
 */
class InvoicePaymentMethodManager extends CustomizableClass
{
    use BillingGateAvailabilityCheckerCreateTrait;
    use EntityFactoryCreateTrait;
    use InvoicePaymentMethodReadRepositoryCreateTrait;
    use InvoicePaymentMethodWriteRepositoryAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $accountId
     * @return string[]
     */
    public function detectApprovedPaymentMethods(?int $accountId): array
    {
        $approvedMethods = [];
        $sm = $this->getSettingsManager();
        $names = Constants\Payment::INVOICE_PAYMENT_METHOD_NAMES;

        if ($sm->get(Constants\Setting::ENABLE_PAYPAL_PAYMENTS, $accountId)) {
            $approvedMethods[Constants\Payment::IPM_PAYPAL] = $names[Constants\Payment::IPM_PAYPAL];
        }

        $isCcAvailable = $this->createBillingGateAvailabilityChecker()->isCcProcessingAvailable($accountId);
        if ($isCcAvailable) {
            $approvedMethods[Constants\Payment::IPM_CC] = $names[Constants\Payment::IPM_CC];
        }

        if (
            $sm->get(Constants\Setting::ACH_PAYMENT, $accountId)
            || $sm->get(Constants\Setting::ACH_PAYMENT_NMI, $accountId)
        ) {
            $approvedMethods[Constants\Payment::IPM_ACH] = $names[Constants\Payment::IPM_ACH];
        }

        if ($sm->get(Constants\Setting::ENABLE_SMART_PAYMENTS, $accountId)) {
            $approvedMethods[Constants\Payment::IPM_SMART_PAY] = $names[Constants\Payment::IPM_SMART_PAY];
        }

        return $approvedMethods;
    }

    /**
     * Set or unset specified set of payment methods for the specified invoice.
     *
     * @param int $invoiceId
     * @param array $methods ($methodId => $methodName)
     * @param int $editorUserId
     */
    public function savePaymentMethods(int $invoiceId, array $methods, int $editorUserId): void
    {
        // Set actual "active" state for payment methods exists in invoice already:
        $invoicePaymentMethods = $this->getPaymentMethods($invoiceId, false);
        foreach ($invoicePaymentMethods as $invoicePaymentMethod) {
            $invoicePaymentMethod->Active = isset($methods[$invoicePaymentMethod->PaymentMethodId]);
            $this->getInvoicePaymentMethodWriteRepository()->saveWithModifier($invoicePaymentMethod, $editorUserId);
            unset($methods[$invoicePaymentMethod->PaymentMethodId]);
        }

        // add InvoicePaymentMethod records for the rest of methods user want to set:
        foreach ($methods as $newMethodId => $methodName) {
            $newInvoicePaymentMethod = $this->createEntityFactory()->invoicePaymentMethod();
            $newInvoicePaymentMethod->Active = true;
            $newInvoicePaymentMethod->InvoiceId = $invoiceId;
            $newInvoicePaymentMethod->PaymentMethodId = $newMethodId;
            $this->getInvoicePaymentMethodWriteRepository()->saveWithModifier($newInvoicePaymentMethod, $editorUserId);
        }
    }

    /**
     * It returns list of payment methods exists in InvoicePaymentMethod repository.
     *
     * @param int $invoiceId
     * @param bool $isActiveOnly default true
     * @return InvoicePaymentMethod[]
     */
    public function getPaymentMethods(int $invoiceId, bool $isActiveOnly = true): array
    {
        $repository = $this->createInvoicePaymentMethodReadRepository()
            ->filterInvoiceId($invoiceId);

        if ($isActiveOnly) {
            $repository->filterActive(true);
        }

        $invoicePaymentMethods = $repository->loadEntities();
        return $invoicePaymentMethods;
    }
}
