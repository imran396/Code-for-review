<?php
/**
 * SAM-6079: Implement lot start closing date
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 8, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Date\Dto;

use Sam\Core\Service\CustomizableClass;
use DateTime;

/**
 * Live and hybrid auction lot dates DTO
 *
 * Class LiveHybridAuctionLotDates
 * @package Sam\AuctionLot\Date
 */
class LiveHybridAuctionLotDates extends CustomizableClass
{
    /** @var DateTime|null */
    private ?DateTime $startDate = null;
    /** @var DateTime|null */
    private ?DateTime $endDate = null;

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
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * @param DateTime|null $startDate
     * @return static
     */
    public function setStartDate(?DateTime $startDate): static
    {
        $this->startDate = $startDate;
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
}
