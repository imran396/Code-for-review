<?php
/**
 * SAM-6079: Implement lot start closing date
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 27, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Date\Dto;

use Sam\Core\Service\CustomizableClass;
use DateTime;

/**
 * Timed auction lot dates DTO
 *
 * Class TimedAuctionLotDates
 * @package Sam\AuctionLot\Date
 */
class TimedAuctionLotDates extends CustomizableClass
{
    /** @var DateTime|null */
    private ?DateTime $startBiddingDate = null;
    /** @var DateTime|null */
    private ?DateTime $endDate = null;
    /** @var DateTime|null */
    private ?DateTime $startClosingDate = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * @return DateTime|null
     */
    public function getStartBiddingDate(): ?DateTime
    {
        return $this->startBiddingDate;
    }

    /**
     * @param DateTime $startBiddingDate
     * @return static
     */
    public function setStartBiddingDate(DateTime $startBiddingDate): static
    {
        $this->startBiddingDate = $startBiddingDate;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    /**
     * @param DateTime $endDate
     * @return static
     */
    public function setEndDate(DateTime $endDate): static
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getStartClosingDate(): ?DateTime
    {
        return $this->startClosingDate;
    }

    /**
     * @param DateTime $startClosingDate
     * @return static
     */
    public function setStartClosingDate(DateTime $startClosingDate): static
    {
        $this->startClosingDate = $startClosingDate;
        return $this;
    }
}
