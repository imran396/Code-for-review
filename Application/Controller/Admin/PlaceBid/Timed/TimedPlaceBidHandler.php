<?php
/** SAM-6481: Add bidding options on the admin - auction - lots - added lots table
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

namespace Sam\Application\Controller\Admin\PlaceBid\Timed;

use Auction;
use AuctionLotItem;
use InvalidArgumentException;
use Sam\Application\Controller\Admin\PlaceBid\PlaceBidCommand;
use Sam\Application\Controller\Admin\PlaceBid\PlaceBidHandlerInterface;
use Sam\Application\Controller\Admin\PlaceBid\Timed\Internal\EventLoggerCreateTrait;
use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidding\BidTransaction\Place\BidDateAwareTrait;
use Sam\Bidding\TimedBid\Place\TimedBidSaverCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Watchlist\WatchlistManagerAwareTrait;
use User;

/**
 * Command handler to place timed auction bid by admin
 *
 * Class TimedPlaceBidHandler
 * @package Sam\Application\Controller\Admin\PlaceBid\Timed
 */
class TimedPlaceBidHandler extends CustomizableClass implements PlaceBidHandlerInterface
{
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BidDateAwareTrait;
    use CookieHelperCreateTrait;
    use EditorUserAwareTrait;
    use EventLoggerCreateTrait;
    use TimedBidSaverCreateTrait;
    use UserLoaderAwareTrait;
    use WatchlistManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param PlaceBidCommand $command
     * @throws \QMySqliDatabaseException
     */
    public function handle(PlaceBidCommand $command): void
    {
        $auction = $this->loadAuction($command->auctionId);
        $user = $this->loadUser($command->bidderId);
        $auctionLot = $this->loadAuctionLotItem($command->lotItemId, $command->auctionId);

        $this->createEventLogger()->log($command->maxBidAmount, $auction, $auctionLot, $user, $this->getEditorUserId());
        $this->createTimedBidSaver()
            ->setBidDateUtc($this->getBidDateUtc())
            ->placeTimedBid(
                $user,
                $auction,
                $command->lotItemId,
                $command->maxBidAmount,
                $this->getEditorUserId(),
                Constants\BidTransaction::TYPE_REGULAR,
                $command->askingBidAmount,
                $this->detectHttpReferrer()
            );
        $this->getWatchlistManager()->autoAdd($user->Id, $command->lotItemId, $auction->Id, $this->getEditorUserId());
    }

    /**
     * @return string
     */
    protected function detectHttpReferrer(): string
    {
        return $this->createCookieHelper()->getHttpReferer();
    }

    /**
     * @param int $auctionId
     * @return Auction
     */
    protected function loadAuction(int $auctionId): Auction
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            throw new InvalidArgumentException('Available auction not found ' . composeSuffix(['a' => $auctionId]));
        }
        return $auction;
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @return AuctionLotItem
     */
    protected function loadAuctionLotItem(int $lotItemId, int $auctionId): AuctionLotItem
    {
        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId);
        if (!$auctionLot) {
            throw new InvalidArgumentException(
                'Available auction lot item not found '
                . composeSuffix(['a' => $auctionId, 'li' => $lotItemId])
            );
        }
        return $auctionLot;
    }

    /**
     * @param int $userId
     * @return User
     */
    protected function loadUser(int $userId): User
    {
        $user = $this->getUserLoader()->load($userId);
        if (!$user) {
            throw new InvalidArgumentException("Available user not found, when placing bid" . composeSuffix(['u' => $userId]));
        }
        return $user;
    }
}
