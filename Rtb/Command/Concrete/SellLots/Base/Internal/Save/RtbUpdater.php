<?php
/**
 * Parent handler for SellLots command coming from any console
 *
 * SAM-6527: Rtb refactor SellLots command
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\SellLots\Base\Internal\Save;

use AuctionLotItem;
use LotItem;
use RtbCurrent;
use Sam\Core\Constants;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Reset\LiveLotResetter;
use Sam\Rtb\Catalog\Bidder\Manage\BidderCatalogManagerFactoryCreateTrait;
use Sam\Rtb\Command\Helper\Base\AbstractRtbCommandHelper;
use Sam\Rtb\Group\GroupingHelperAwareTrait;
use Sam\Rtb\Sell\SellLotNotifierCreateTrait;
use Sam\Rtb\State\Reset\RtbStateResetterAwareTrait;
use Sam\Rtb\State\RtbStateUpdaterCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;
use User;

/**
 * Class RtbUpdater
 * @package Sam\Rtb
 */
class RtbUpdater extends CustomizableClass
{
    use AuctionLotItemWriteRepositoryAwareTrait;
    use BidderCatalogManagerFactoryCreateTrait;
    use CurrentDateTrait;
    use GroupingHelperAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotItemWriteRepositoryAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbStateResetterAwareTrait;
    use RtbStateUpdaterCreateTrait;
    use SellLotNotifierCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Clear group from sold lots
     * @param RtbCurrent $rtbCurrent
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     */
    public function prepareGroupedLots(RtbCurrent $rtbCurrent, AuctionLotItem $auctionLot, int $editorUserId): void
    {
        $this->getGroupingHelper()->clearGroupFromSold($rtbCurrent->AuctionId);
        if (
            $rtbCurrent->LotGroup === Constants\Rtb::GROUP_CHOICE
            && $this->canSellLot($auctionLot)
        ) {
            LiveLotResetter::new()->reset(
                $auctionLot->LotItemId,
                $auctionLot->AuctionId,
                $editorUserId
            );
        }
    }

    /**
     * Performs recalculation and update of rtb state
     * @param RtbCurrent $rtbCurrent
     * @param LotItem|null $nextLotItem null when no next lot item
     * @param AbstractRtbCommandHelper $commandHelper
     * @param int $editorUserId
     * @param int $systemAccountId
     * @param int $viewLanguageId
     * @return RtbCurrent
     */
    public function updateRtbState(
        RtbCurrent $rtbCurrent,
        ?LotItem $nextLotItem,
        AbstractRtbCommandHelper $commandHelper,
        int $editorUserId,
        int $systemAccountId,
        int $viewLanguageId
    ): RtbCurrent {
        $rtbCurrent = $commandHelper->switchRunningLot($rtbCurrent, $nextLotItem);
        $rtbCurrent = $this->getRtbStateResetter()
            ->enableUngroup(false)
            ->cleanState($rtbCurrent, $editorUserId);
        $rtbCurrent = $commandHelper->activateLot($rtbCurrent, Constants\Rtb::LA_BY_AUTO_START, $editorUserId);
        $rtbCurrent = $this->createRtbStateUpdater()->update($rtbCurrent, $systemAccountId, $viewLanguageId);
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $editorUserId);
        return $rtbCurrent;
    }

    /**
     * Since this function performs side effect on lots located in input argument,
     * thus it returns modified auction lot array.
     * @param RtbCurrent $rtbCurrent
     * @param AuctionLotItem[] $auctionLots
     * @param User|null $winnerBidUser
     * @param int|null $winnerUserId
     * @param float $hammerPrice
     * @param bool $isInternetBid
     * @param int $editorUserId
     * @param int $systemAccountId
     * @param int $viewLanguageId
     * @return AuctionLotItem[]
     */
    public function updateLots(
        RtbCurrent $rtbCurrent,
        array $auctionLots,
        ?User $winnerBidUser,
        ?int $winnerUserId,
        float $hammerPrice,
        bool $isInternetBid,
        int $editorUserId,
        int $systemAccountId,
        int $viewLanguageId
    ): array {
        if (!$auctionLots) {
            return [];
        }

        foreach ($auctionLots as $i => $auctionLot) {
            $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
            if (!$lotItem) {
                log_error(
                    "Available lot item not found, when selling grouped lots via admin console"
                    . composeSuffix(['li' => $auctionLot->LotItemId, 'ali' => $auctionLot->Id])
                );
                continue;
            }

            $lotItem->assignSoldInfo($rtbCurrent->AuctionId, $this->getCurrentDateUtc(), $hammerPrice, $isInternetBid, $winnerUserId);
            $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $editorUserId);

            $auctionLot->toSold();
            $this->getAuctionLotItemWriteRepository()->save($auctionLot); // IK: it was ->forceSave($auctionLot), IDK why (SAM-5436)

            $auctionLots[$i] = $auctionLot;

            if ($winnerBidUser) {
                $this->createSellLotNotifier()->sendWinningBidderNotification(
                    $winnerBidUser,
                    $lotItem,
                    $auctionLot,
                    $editorUserId
                );
            }

            $catalogManager = $this->createCatalogManagerFactory()
                ->createByRtbCurrent($rtbCurrent, $systemAccountId, $viewLanguageId);
            $catalogManager->updateRow($auctionLot);
        }

        return $auctionLots;
    }


    /**
     * Checks, if lot can still be sold
     * @param AuctionLotItem $auctionLot
     * @return bool
     */
    protected function canSellLot(AuctionLotItem $auctionLot): bool
    {
        return $auctionLot->isActiveOrUnsold();
    }
}
