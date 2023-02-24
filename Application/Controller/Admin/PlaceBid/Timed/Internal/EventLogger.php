<?php
/**
 * SAM-6481: Add bidding options on the admin - auction - lots - added lots table
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\PlaceBid\Timed\Internal;

use Auction;
use AuctionLotItem;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuditTrail\AuditTrailLoggerAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidding\BidTransaction\Validate\BidTransactionExistenceCheckerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use User;

/**
 * Responsible for logging data about placing a bid by admin in the audit trail
 *
 * Class EventLogger
 * @package Sam\Application\Controller\Admin\PlaceBid\Timed\Internal
 * @internal
 */
class EventLogger extends CustomizableClass
{
    use AuctionBidderLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use AuditTrailLoggerAwareTrait;
    use BidTransactionExistenceCheckerAwareTrait;
    use CurrencyLoaderAwareTrait;
    use LotRendererAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Place bid data to audit log
     *
     * @param float $maxBidAmount
     * @param Auction $auction
     * @param AuctionLotItem $auctionLot
     * @param User $user
     * @param int $editorUserId
     */
    public function log(
        float $maxBidAmount,
        Auction $auction,
        AuctionLotItem $auctionLot,
        User $user,
        int $editorUserId
    ): void {
        $eventMessage = $this->makeEventMessage($maxBidAmount, $auction, $auctionLot, $user);
        $this->getAuditTrailLogger()
            ->setAccountId($auction->AccountId)
            ->setEditorUserId($editorUserId)
            ->setEvent($eventMessage)
            ->setSection('auctions/catalog')
            ->setUserId($editorUserId)
            ->log();
    }

    /**
     * @param float $maxBidAmount
     * @param Auction $auction
     * @param AuctionLotItem $auctionLot
     * @param User $user
     * @return string
     */
    protected function makeEventMessage(float $maxBidAmount, Auction $auction, AuctionLotItem $auctionLot, User $user): string
    {
        $bidderNum = $this->detectBidderNum($user->Id, $auction->Id);
        $eventAction = $this->makeEventActionName($auctionLot->LotItemId, $auction->Id, $user->Id);
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign($auction->Id);
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $auctionName = $this->getAuctionRenderer()->renderName($auction, true);

        $eventMessage = "{$user->Username} (bidder ({$bidderNum}) {$eventAction} of {$currencySign} {$maxBidAmount} "
            . "on lot {$lotNo}({$auctionLot->Id}), auction {$auctionName}($auction->Id)";
        return $eventMessage;
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param int $userId
     * @return string
     */
    protected function makeEventActionName(int $lotItemId, int $auctionId, int $userId): string
    {
        $hasBidPlaced = $this->createBidTransactionExistenceChecker()->exist($userId, $lotItemId, $auctionId);
        $bidActionText = ($hasBidPlaced ? 'changes' : 'places') . ' max bid';
        return $bidActionText;
    }

    /**
     * @param int $userId
     * @param int $auctionId
     * @return string
     */
    protected function detectBidderNum(int $userId, int $auctionId): string
    {
        $auctionBidder = $this->getAuctionBidderLoader()->load($userId, $auctionId, true);
        return $auctionBidder->BidderNum ?? '';
    }
}
