<?php
/**
 * SAM-4679: Refactor auction phone bidder report
 * https://bidpath.atlassian.net/browse/SAM-4679
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

namespace Sam\Report\Auction\PhoneBidder;

/**
 * Trait for mutual filtering properties in Auction Phone Bidder report
 * @package Sam\Report\Auction\PhoneBidder
 */
trait FilterAwareTrait
{
    protected string $clerk = '';
    protected bool $isUnassignedOnly = false;
    protected bool $isAllLots = false;
    protected ?int $minLotNum = null;
    protected ?int $maxLotNum = null;
    protected ?int $bidderId = null;

    /**
     * Set bidderId property value and normalize integer value
     * @param int|null $bidderId
     * @return static
     */
    public function filterBidderId(?int $bidderId): static
    {
        $this->bidderId = $bidderId;
        return $this;
    }

    /**
     * Return value of bidderId property
     * @return int|null
     */
    public function getBidderId(): ?int
    {
        return $this->bidderId;
    }

    /**
     * @param string $clerk
     * @return static
     */
    public function filterClerk(string $clerk): static
    {
        $this->clerk = trim($clerk);
        return $this;
    }

    /**
     * @return string
     */
    public function getClerk(): string
    {
        return $this->clerk;
    }

    /**
     * @param int|null $minLotNum
     * @return static
     */
    public function filterMinLotNum(?int $minLotNum): static
    {
        $this->minLotNum = $minLotNum;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinLotNum(): ?int
    {
        return $this->minLotNum;
    }

    /**
     * @param int|null $maxLotNum
     * @return static
     */
    public function filterMaxLotNum(?int $maxLotNum): static
    {
        $this->maxLotNum = $maxLotNum;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxLotNum(): ?int
    {
        return $this->maxLotNum;
    }

    /**
     * @param bool|null $unassignedOnly
     * @return static
     */
    public function enableUnassignedOnly(bool $unassignedOnly): static
    {
        $this->isUnassignedOnly = $unassignedOnly;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function isUnassignedOnly(): ?bool
    {
        return $this->isUnassignedOnly;
    }

    /**
     * @param bool|null $allLots
     * @return static
     */
    public function enableAllLots(bool $allLots): static
    {
        $this->isAllLots = $allLots;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function isAllLots(): ?bool
    {
        return $this->isAllLots;
    }
}
