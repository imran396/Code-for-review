<?php
/**
 * Invoice Entity availability filtering definition logic. It is intended for usage in Entity Loaders and Existence Checkers
 *
 * SAM-4922: Entity Loader and Existence Checker approach integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           02/28/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\Availability;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;

/**
 * Trait FilterInvoiceAvailabilityAwareTrait
 * @package Sam\Core\Filter\Availability
 */
trait FilterInvoiceAvailabilityAwareTrait
{
    /**
     * Filter results by these statuses
     * @var int[]|null
     */
    private ?array $filterInvoiceStatusId = null;

    /**
     * Define filtering by user statuses
     * @param int|int[] $invoiceStatusId
     * @return static
     */
    public function filterInvoiceStatusId(int|array $invoiceStatusId): static
    {
        $this->filterInvoiceStatusId = ArrayCast::makeIntArray($invoiceStatusId, Constants\Invoice::$invoiceStatuses);
        return $this;
    }

    /**
     * Drop any filtering, so we get un-conditional loading
     * @return static
     */
    protected function clearFilterInvoice(): static
    {
        $this->dropFilterInvoiceStatusId();
        return $this;
    }

    /**
     * Drop filtering by u.user_status_id
     * @return static
     */
    protected function dropFilterInvoiceStatusId(): static
    {
        $this->filterInvoiceStatusId = null;
        return $this;
    }

    /**
     * @return array|null
     */
    protected function getFilterInvoiceStatusId(): ?array
    {
        return $this->filterInvoiceStatusId;
    }

    /**
     * @return bool
     */
    protected function hasFilterInvoiceStatusId(): bool
    {
        return $this->filterInvoiceStatusId !== null;
    }
}
