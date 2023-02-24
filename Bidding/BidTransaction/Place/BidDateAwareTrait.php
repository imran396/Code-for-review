<?php
/**
 * We want to use the same date on each step of bidding process:
 * during bid validation, bid transaction create, end date auto extend, lot closing during bidding (ExtendAll+StaggerClosing)
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/5/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BidTransaction\Place;

use DateTime;
use DateTimeZone;
use Sam\Date\DateAdapter;

/**
 * Trait BidDateAwareTrait
 * @package Sam\Bidding\BidTransaction\Place
 */
trait BidDateAwareTrait
{
    /**
     * @var DateAdapter|null
     */
    protected ?DateAdapter $bidDateAdapter = null;

    /**
     * @return DateTime|null
     */
    public function getBidDateUtc(): ?DateTime
    {
        return $this->getBidDateAdapter(true)->getDateUtc();
    }

    /**
     * @return int
     */
    public function getBidDateUtcTimestamp(): int
    {
        return $this->getBidDateAdapter(true)->getDateUtcTimestamp();
    }

    /**
     * @return string|null
     */
    public function getBidDateUtcIso(): ?string
    {
        return $this->getBidDateAdapter(true)->getDateUtcIso();
    }

    /**
     * @param DateTime|null $bidDateUtc
     * @return static
     */
    public function setBidDateUtc(?DateTime $bidDateUtc): static
    {
        $this->getBidDateAdapter()->setDateUtc($bidDateUtc);
        return $this;
    }

    /**
     * @param bool $initNewByNow init by current date, if it is new bid date adapter
     * @return DateAdapter
     */
    private function getBidDateAdapter(bool $initNewByNow = false): DateAdapter
    {
        if ($this->bidDateAdapter === null) {
            $this->bidDateAdapter = DateAdapter::new();
            if ($initNewByNow) {
                $this->initBidDateByNow();
            }
        }
        return $this->bidDateAdapter;
    }

    /**
     * @return static
     */
    public function initBidDateByNow(): static
    {
        $currentDateUtc = (new DateTime())->setTimezone(new DateTimeZone('UTC'));
        $this->setBidDateUtc($currentDateUtc);
        return $this;
    }
}
