<?php
/**
 * SAM-5952: Extract auction and lot dates recalculation from AuctionEditForm
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 07, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionEditForm\Date;


use Auction;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Date\StartEndPeriod\TimedAuctionDateAssignorAwareTrait;
use Sam\AuctionLot\Date\AuctionLotDatesUpdaterCreateTrait;
use Sam\Lot\Count\LotCounterAwareTrait;

/**
 * Class AuctionAndLotDatesRecalculator
 * @package Sam\View\Admin\Form\AuctionEditForm\Date
 */
class AuctionAndLotDatesRecalculator extends CustomizableClass
{
    use AuctionLotDatesUpdaterCreateTrait;
    use LotCounterAwareTrait;
    use TimedAuctionDateAssignorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param int $dateAssignmentStrategy
     * @param int $editorUserId
     */
    public function recalculate(int $auctionId, int $dateAssignmentStrategy, int $editorUserId): void
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isAuctionToItemsDateAssignment($dateAssignmentStrategy)) {
            $this->createAuctionLotDatesUpdater()->update($auctionId, $editorUserId);
        } else {
            $this->getTimedAuctionDateAssignor()
                ->setAuctionId($auctionId)
                ->updateDateFromLots($editorUserId);
        }
    }

    /**
     * @param Auction|null $sourceAuction
     * @param Auction $targetAuction
     * @return bool
     */
    public function shouldAskUser(?Auction $sourceAuction, Auction $targetAuction): bool
    {
        if (
            $targetAuction->isTimedScheduled()
            && $targetAuction->isAuctionToItemsDateAssignment()
            && !$targetAuction->ExtendAll
            && $this->isAuctionDateRelatedPropertiesChanged($sourceAuction, $targetAuction)
            && !$this->isAuctionEmpty($targetAuction)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param Auction $auction
     * @return bool
     */
    protected function isAuctionEmpty(Auction $auction): bool
    {
        $lotCount = $this->getLotCounter()
            ->setAuction($auction)
            ->count();
        return $lotCount === 0;
    }

    /**
     * @param Auction|null $sourceAuction
     * @param Auction $targetAuction
     * @return bool
     */
    protected function isAuctionDateRelatedPropertiesChanged(?Auction $sourceAuction, Auction $targetAuction): bool
    {
        $shouldApply = false;
        $isNewAuction = !$sourceAuction || $sourceAuction->Id !== $targetAuction->Id;
        if ($isNewAuction) {
            $shouldApply = true;
        } elseif ($targetAuction->DateAssignmentStrategy !== $sourceAuction->DateAssignmentStrategy) {
            $shouldApply = true;
        } elseif ($targetAuction->StartBiddingDate != $sourceAuction->StartBiddingDate) {
            $shouldApply = true;
        } elseif ($targetAuction->StartClosingDate != $sourceAuction->StartClosingDate) {
            $shouldApply = true;
        } elseif ($targetAuction->StaggerClosing !== $sourceAuction->StaggerClosing) {
            $shouldApply = true;
        } elseif ($targetAuction->LotsPerInterval !== $sourceAuction->LotsPerInterval) {
            $shouldApply = true;
        }
        return $shouldApply;
    }
}
