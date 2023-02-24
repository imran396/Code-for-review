<?php
/**
 * "Buy Now" function availability checking.
 * This core class implements detection logic of Buy Now function availability for lot.
 * It isn't related to persistence layer.
 *
 * SAM-4264: Lot can be purchased by price lower than Max Bid value of another bidder using Buy Now function
 *
 * Currently we work according 1.b strategy: “Disable Buy Now function, as soon as the first bid is placed”
 * Previous 1.a strategy is disabled for future improvements: 1.a: “Disable Buy Now function, if Current Bid reaches Buy Now amount”
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Nov 04, 2015
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * More info:
 * https://bidpath.atlassian.net/browse/SAM-4458?focusedCommentId=116281&page=com.atlassian.jira.plugin.system.issuetabpanels%3Acomment-tabpanel#comment-116281
 */

namespace Sam\Core\Bidding\BuyNow;

/**
 * We require used classes, because we want to use this class in data provider for synchronization
 * lot info at public side (Sam\AuctionLot\Sync\PublicDataProvider)
 * and we don't use there autoloader in optimization purposes.
 * TODO: we need to solve this case, possibly implement some light autoloader for it (PSR-4)
 */

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Math\Floating;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class BuyNowAvailabilityTimedChecker
 * @package Sam\Core\Bidding\BuyNow
 */
class BuyNowAvailabilityTimedChecker extends BuyNowAvailabilityCheckerBase
{
    use ConfigRepositoryAwareTrait;

    /**
     * alic.current_bid
     */
    protected ?float $currentBid = null;
    /**
     * Restriction strategy
     */
    protected ?string $restriction = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if "Buy Now" action is available for timed lot
     * @return bool
     */
    public function isAvailable(): bool
    {
        $this->notifications = [];
        $currentDateUtc = $this->getCurrentDateUtc();
        if (
            !$this->getStartDateUtc()
            || $currentDateUtc < $this->getStartDateUtc()
        ) { // not yet started
            $isStarted = false;
            $isEnded = false;
        } elseif (
            $this->getEndDateUtc()
            && $currentDateUtc < $this->getEndDateUtc()
        ) { // not yet ended
            $isStarted = true;
            $isEnded = false;
        } else { // Ended
            $isStarted = true;
            $isEnded = true;
        }

        $this->checkMutualConditions($isEnded);

        if (
            $this->getRestriction() === Constants\Auction::BNTR_FIRST_BID
            && Floating::gt($this->getCurrentBid(), 0)
        ) {
            $this->notifications[] = self::N_RESTRICTION_FIRST_BID;
        } elseif ($this->getRestriction() === Constants\Auction::BNTR_CURRENT_BID) {
            if (
                Floating::gt($this->getBuyNowAmount(), 0)
                && Floating::lteq($this->getBuyNowAmount(), $this->getCurrentBid())
            ) {
                $this->notifications[] = self::N_NOT_MEET_CURRENT_BID;
            }
        }

        if (!$isStarted) {
            $this->notifications[] = self::N_NOT_STARTED;
        }

        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        if (!$auctionLotStatusPureChecker->isActive($this->getLotStatus())) {
            $this->notifications[] = self::N_STATUS_NOT_ACTIVE;
        }

        $isAvailable = count($this->notifications) === 0;
        return $isAvailable;
    }

    /**
     * @return float|null
     */
    public function getCurrentBid(): ?float
    {
        return $this->currentBid;
    }

    /**
     * @param float|null $currentBid
     * @return static
     */
    public function setCurrentBid(?float $currentBid): static
    {
        $this->currentBid = $currentBid;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRestriction(): ?string
    {
        if ($this->restriction === null) {
            $this->restriction = $this->cfg()->get('core->bidding->buyNow->timed->restriction');
        }
        return $this->restriction;
    }

    /**
     * @param string $restriction
     * @return static
     */
    public function setRestriction(string $restriction): static
    {
        $this->restriction = Cast::toString($restriction, Constants\Auction::BUY_NOW_TIMED_RESTRICTIONS);
        return $this;
    }
}
