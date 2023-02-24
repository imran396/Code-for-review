<?php
/**
 * SAM-4622: Refactor inventory report
 * https://bidpath.atlassian.net/browse/SAM-4622
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

namespace Sam\Report\Lot\Inventory;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;

/**
 * Trait for mutual filtering properties in
 * @package Sam\Report\Lot\Inventory
 */
trait FilterAwareTrait
{
    protected ?int $consignorUserId = null;
    protected ?int $lotCategoryId = null;
    protected ?int $overallLotStatus = null;
    protected string $groupId = '';
    protected string $searchKey = '';

    /**
     * @param int|null $consignorUserId
     * @return static
     */
    public function filterConsignorUserId(?int $consignorUserId): static
    {
        $this->consignorUserId = $consignorUserId;
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
     * @param int|null $lotCategoryId
     * @return static
     */
    public function filterLotCategoryId(?int $lotCategoryId): static
    {
        $this->lotCategoryId = $lotCategoryId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLotCategoryId(): ?int
    {
        return $this->lotCategoryId;
    }

    /**
     * @param string $groupId
     * @return static
     */
    public function filterGroupId(string $groupId): static
    {
        $this->groupId = $groupId;
        return $this;
    }

    /**
     * @return string
     */
    public function getGroupId(): string
    {
        return $this->groupId;
    }

    /**
     * Filter by lot status, this is not only ali.lot_status_id
     * @param int|null $status
     * @return static
     */
    public function filterOverallLotStatus(?int $status): static
    {
        $this->overallLotStatus = Cast::toInt($status, Constants\MySearch::$inventoryLotStatusFilters);
        return $this;
    }

    /**
     * Get filter by lot status, this is not only ali.lot_status_id
     * @return int|null
     */
    public function getOverallLotStatus(): ?int
    {
        return $this->overallLotStatus;
    }

    /**
     * @param string $searchKey
     * @return static
     */
    public function filterSearchKey(string $searchKey): static
    {
        $this->searchKey = trim($searchKey);
        return $this;
    }

    /**
     * @return string
     */
    public function getSearchKey(): string
    {
        return $this->searchKey;
    }
}
