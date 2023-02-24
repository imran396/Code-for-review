<?php
/**
 * SAM-4625: Refactor settlement list report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/15/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Settlement\SettlementList;

/**
 * Trait for mutual filtering properties in SettlementList report
 * @package Sam\Report\Settlement\SettlementList
 */
trait FilterAwareTrait
{
    protected ?int $consignorUserId = null;
    protected ?int $settlementStatusId = null;
    protected bool $isAccountFiltering = false;

    /**
     * @return bool
     */
    public function isAccountFiltering(): bool
    {
        return $this->isAccountFiltering;
    }

    public function enableAccountFiltering(bool $enable): static
    {
        $this->isAccountFiltering = $enable;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSettlementStatusId(): ?int
    {
        return $this->settlementStatusId;
    }

    /**
     * @param int|null $settlementStatus
     * @return static
     */
    public function filterSettlementStatusId(?int $settlementStatus): static
    {
        $this->settlementStatusId = $settlementStatus;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getConsignorUserId(): ?int
    {
        return $this->consignorUserId;
    }

    /**
     * @param int|null $consignorUserId
     * @return static
     */
    public function filterConsignorUserId(?int $consignorUserId): static
    {
        $this->consignorUserId = $consignorUserId;
        return $this;
    }
}
