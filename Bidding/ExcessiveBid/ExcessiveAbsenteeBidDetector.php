<?php
/**
 * Calculate a bid amount depends if can or not reveal absentee bid\asking bid
 * SAM-5229: Outrageous bid alert reveals hidden high absentee bid
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igors Kotelvskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           18 Jul, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\ExcessiveBid;

use AbsenteeBid;
use Auction;
use InvalidArgumentException;
use Sam\Core\Service\CustomizableClass;
use Sam\Bidding\AbsenteeBid\Load\AbsenteeBidLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionLotAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;

/**
 * Class ExcessiveBidHelper
 * @package Sam\Bidding\ExcessiveBid
 */
class ExcessiveAbsenteeBidDetector extends CustomizableClass
{
    use AbsenteeBidLoaderAwareTrait;
    use AssumedAskingBidLiveCalculatorAwareTrait;
    use AuctionAwareTrait;
    use AuctionLotAwareTrait;
    use UserAwareTrait;

    protected ?AbsenteeBid $userAbsenteeBid = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $editorUserId
     * @return float
     */
    public function detectAssumedAskingBid(int $editorUserId): float
    {
        $auction = $this->getAuction();
        $auctionLotCache = $this->getAuctionLotCacheOrCreatePersisted($editorUserId);
        $absenteeBid = $this->getUserAbsenteeBid();
        $assumedAskingBid = $this->getAssumedAskingBidLiveCalculator()->calc(
            (float)$auctionLotCache->AskingBid,
            $absenteeBid ? (float)$absenteeBid->MaxBid : null,
            (float)$auctionLotCache->StartingBidNormalized,
            $auction->NotifyAbsenteeBidders,
            $auction->AbsenteeBidsDisplay
        );
        return $assumedAskingBid;
    }

    /**
     * @override AuctionAwareTrait::getAuction()
     * @return Auction
     */
    public function getAuction(): Auction
    {
        if ($this->getAuctionAggregate()->getAuction() === null) {
            $this->setAuctionId($this->getAuctionLot()->AuctionId);
        }
        $auction = $this->getAuctionAggregate()->getAuction();
        if (!$auction) {
            $message = "Cannot detect excessive bid, because auction not found";
            log_error($message);
            throw new InvalidArgumentException($message);
        }
        return $auction;
    }

    /**
     * @return AbsenteeBid
     */
    public function getUserAbsenteeBid(): ?AbsenteeBid
    {
        if ($this->userAbsenteeBid === null) {
            $auctionLot = $this->getAuctionLot();
            if (!$auctionLot) {
                $message = "Cannot detect excessive bid, because auction lot not found";
                log_error($message);
                throw new InvalidArgumentException($message);
            }
            $this->userAbsenteeBid = $this->getAbsenteeBidLoader()
                ->load($auctionLot->LotItemId, $auctionLot->AuctionId, $this->getUserId());
        }
        return $this->userAbsenteeBid;
    }

    /**
     * @param AbsenteeBid|null $userAbsenteeBid
     * @return static
     */
    public function setUserAbsenteeBid(?AbsenteeBid $userAbsenteeBid): static
    {
        $this->userAbsenteeBid = $userAbsenteeBid;
        return $this;
    }
}
