<?php
/**
 * SAM-3224:Refactoring of auction_closer.php
 * https://bidpath.atlassian.net/browse/SAM-3224
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 23, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Closer;

use AuctionLotItem;
use BidTransaction;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Email_Template;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Date\AuctionLotDateAssignorCreateTrait;
use Sam\AuctionLot\Date\Dto\TimedAuctionLotDates;
use Sam\AuctionLot\Load\TimedItemLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionLotAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\BidderPrivilegeChecker;

/**
 * Class SingleTimedLotCloser
 * @package Sam\AuctionLot\Closer
 */
class SingleTimedLotCloser extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotAwareTrait;
    use AuctionLotDateAssignorCreateTrait;
    use LotItemLoaderAwareTrait;
    use LotItemWriteRepositoryAwareTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use TimedItemLoaderAwareTrait;
    use UserAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * @var BidTransaction|null
     */
    protected ?BidTransaction $winnerBid = null;
    /**
     * @var float|null
     */
    protected ?float $winAmount = null;
    /**
     * @var string|null
     */
    protected ?string $closeBidType = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return BidTransaction
     */
    public function getWinnerBid(): ?BidTransaction
    {
        return $this->winnerBid;
    }

    /**
     * @param BidTransaction|null $winnerBid
     * @return static
     */
    public function setWinnerBid(?BidTransaction $winnerBid): static
    {
        $this->winnerBid = $winnerBid;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getWinAmount(): ?float
    {
        return $this->winAmount;
    }

    /**
     * @param float|null $winAmount . Default value is null
     * @return static
     */
    public function setWinAmount(?float $winAmount): static
    {
        $this->winAmount = $winAmount;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCloseBidType(): ?string
    {
        return $this->closeBidType;
    }

    /**
     * @param string|null $bidType
     * @return static
     */
    public function setCloseBidType(?string $bidType): static
    {
        $this->closeBidType = Cast::toString($bidType, Constants\BidTransaction::$types);
        return $this;
    }

    /**
     * @param BidTransaction|null $winnerBid . It is null for instant purchase by "Buy Now", and for "Offer" case.
     * @param AuctionLotItem $auctionLot
     * @param float|null $winAmount . It can be null when no win amount is set
     * @param string $closeBidType
     * @param int $editorUserId
     * @param int|null $userId
     * @return void
     */
    public function closeSingleLotBy(
        ?BidTransaction $winnerBid,
        AuctionLotItem $auctionLot,
        ?float $winAmount,
        string $closeBidType,
        int $editorUserId,
        ?int $userId = null
    ): void {
        $this->setWinnerBid($winnerBid)
            ->setAuctionLot($auctionLot)
            ->setWinAmount($winAmount)
            ->setCloseBidType($closeBidType)
            ->setUserId($userId)
            ->closeSingleLot($editorUserId);
    }

    /**
     * @param int $editorUserId
     * @return void
     */
    public function closeSingleLot(int $editorUserId): void
    {
        // Reload this first to make sure the lot item has not yet been closed
        $auctionLot = $this->getAuctionLot();
        if (!$auctionLot) {
            log_error("Auction lot not found, when closing single lot");
            return;
        }
        $auctionLot->Reload();
        $closeBidType = $this->getCloseBidType();
        if (
            $closeBidType !== Constants\BidTransaction::TYPE_OFFER
            && !$auctionLot->isActive()
        ) {
            return;
        }

        $logData = ['li' => $auctionLot->LotItemId, 'a' => $auctionLot->AuctionId, 'ali' => $this->getAuctionLotId()];
        $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
        if (!$lotItem) {
            log_error('Available lot item not found for closing' . composeSuffix($logData));
            return;
        }

        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if (!$auction) {
            log_error('Available auction not found, when closing lot' . composeSuffix($logData));
            return;
        }

        $winnerBid = $this->getWinnerBid();
        $userId = $this->getUserId() ?? $winnerBid->UserId ?? null;

        $user = $this->getUserLoader()->load($userId);
        $logData = ['u' => $userId] + $logData;
        if (!$user) {
            log_error('Available winning bidder user not found, when closing lot' . composeSuffix($logData));
            return;
        }

        $hammerPrice = $this->getWinAmount() ?? $winnerBid->Bid ?? null;

        $endDateDetector = EndDateDetector::new()
            ->setCloseBidType($closeBidType)
            ->setAuction($auction)
            ->setAuctionLot($auctionLot);
        $auctionLotDates = TimedAuctionLotDates::new()->setEndDate($endDateDetector->detectLotEndDate());
        $this->createAuctionLotDateAssignor()->assignForTimed($auctionLot, $auctionLotDates, $editorUserId);

        $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($auctionLot->LotItemId, $auctionLot->AuctionId);
        $lotItem->assignSoldInfo(
            $auctionLot->AuctionId, // Should also update sale/auction where it has been sold
            $endDateDetector->detectLotSoldDate(),
            $auctionLot->multiplyQuantityEffectively($hammerPrice, $quantityScale),
            true,
            $user->Id
        );

        // SAM-2991: wipe out lot sold info, if winner is House Bidder
        $hasPrivilegeForHouseBidder = BidderPrivilegeChecker::new()
            ->initByUser($user)
            ->hasPrivilegeForHouse();
        if ($hasPrivilegeForHouseBidder) {
            $lotItem->wipeOutSoldInfo();
            log_info('House Bidder wins lot and lot marked unsold' . composeSuffix($logData));
        }
        $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $editorUserId);
        if (!$hasPrivilegeForHouseBidder) {
            // Send Email
            if ($closeBidType === Constants\BidTransaction::TYPE_BUY_NOW) {
                $emailKey = $auction->Reverse
                    ? Constants\EmailKey::BUY_NOW_ADMIN_REVERSE
                    : Constants\EmailKey::BUY_NOW_ADMIN;
                $emailManager = Email_Template::new()->construct(
                    $lotItem->AccountId,
                    $emailKey,
                    $editorUserId,
                    [$user, $lotItem, $auctionLot],
                    $auctionLot->AuctionId
                );
                $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);
                $emailManager = Email_Template::new()->construct(
                    $lotItem->AccountId,
                    Constants\EmailKey::WINNING_BID_NOTIFICATION_SENT_CONSIGNOR,
                    $editorUserId,
                    [$user, $lotItem, $auctionLot],
                    $auctionLot->AuctionId
                );
                $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);
            } else {
                LotCloseNotifier::new()
                    ->setAuctionLot($auctionLot)
                    ->setLotItem($lotItem)
                    ->setUser($user)
                    ->notifyLotWon($editorUserId);
            }
        }
    }
}
