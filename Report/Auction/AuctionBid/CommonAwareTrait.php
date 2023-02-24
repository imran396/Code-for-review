<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           4/28/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\AuctionBid;

/**
 * Trait CommonAwareTrait
 * @package Sam\Report\Auction\AuctionBid
 */
trait CommonAwareTrait
{
    protected string $dateRangeType = '';
    protected bool $isDateFiltering = false;

    /**
     * @return bool
     */
    public function isDateFiltering(): bool
    {
        return $this->isDateFiltering;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableDateFiltering(bool $enable): static
    {
        $this->isDateFiltering = $enable;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateRangeType(): string
    {
        return $this->dateRangeType;
    }

    /**
     * @param string $dateRangeType
     * @return static
     */
    public function setDateRangeType(string $dateRangeType): static
    {
        $this->dateRangeType = $dateRangeType;
        return $this;
    }
}
