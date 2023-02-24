<?php
/**
 * SAM-6830: Enrich Invoice entity
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\Invoice\Status;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class InvoiceStatusPureChecker
 * @package Sam\Core\Entity\Model\Invoice\Status
 */
class InvoiceStatusPureChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $invoiceStatus
     * @return bool
     */
    public function isOpen(?int $invoiceStatus): bool
    {
        return $invoiceStatus === Constants\Invoice::IS_OPEN;
    }

    /**
     * @param int|null $invoiceStatus
     * @return bool
     */
    public function isPending(?int $invoiceStatus): bool
    {
        return $invoiceStatus === Constants\Invoice::IS_PENDING;
    }

    /**
     * @param int|null $invoiceStatus
     * @return bool
     */
    public function isPaid(?int $invoiceStatus): bool
    {
        return $invoiceStatus === Constants\Invoice::IS_PAID;
    }

    /**
     * @param int|null $invoiceStatus
     * @return bool
     */
    public function isShipped(?int $invoiceStatus): bool
    {
        return $invoiceStatus === Constants\Invoice::IS_SHIPPED;
    }

    /**
     * @param int|null $invoiceStatus
     * @return bool
     */
    public function isCanceled(?int $invoiceStatus): bool
    {
        return $invoiceStatus === Constants\Invoice::IS_CANCELED;
    }

    /**
     * @param int|null $invoiceStatus
     * @return bool
     */
    public function isDeleted(?int $invoiceStatus): bool
    {
        return $invoiceStatus === Constants\Invoice::IS_DELETED;
    }

    /**
     * @param int|null $invoiceStatus
     * @return bool
     */
    public function isPaidOrShipped(?int $invoiceStatus): bool
    {
        return $this->isPaid($invoiceStatus) || $this->isShipped($invoiceStatus);
    }

    /**
     * Check, if invoice status is any of all (including deleted)
     * @param int|null $invoiceStatus
     * @return bool
     */
    public function isAmongAllStatuses(?int $invoiceStatus): bool
    {
        return in_array($invoiceStatus, Constants\Invoice::$invoiceStatuses, true);
    }

    /**
     * Check, if invoice status is among available statuses (skip deleted)
     * @param int|null $invoiceStatus
     * @return bool
     */
    public function isAmongAvailableInvoiceStatuses(?int $invoiceStatus): bool
    {
        return in_array($invoiceStatus, Constants\Invoice::$availableInvoiceStatuses, true);
    }

    public function isLegacyTaxDesignation(?int $taxDesignation): bool
    {
        return $taxDesignation === Constants\Invoice::TDS_LEGACY;
    }

    public function isStackedTaxDesignation(?int $taxDesignation): bool
    {
        return $taxDesignation === Constants\Invoice::TDS_STACKED_TAX;
    }
}
