<?php
/**
 * Help methods for different `auction_bidder` related validations
 *
 * SAM-3905: Auction bidder checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 28, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Validate;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Auction\Bidder\AuctionBidderPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepositoryCreateTrait;
use Sam\User\Flag\UserFlaggingAwareTrait;

/**
 * Class AuctionBidderChecker
 * @package Sam\Bidder\AuctionBidder\Validate
 */
class AuctionBidderChecker extends CustomizableClass
{
    use AuctionBidderLoaderAwareTrait;
    use AuctionBidderReadRepositoryCreateTrait;
    use AuctionLoaderAwareTrait;
    use BidderNumPaddingAwareTrait;
    use UserFlaggingAwareTrait;

    public const OP_CHECK_NAA = 'checkNaa'; // bool
    public const OP_ACCOUNT_ID = 'accountId'; // ?int
    public const OP_BIDDER_NUM = 'bidderNum'; // ?string

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check, if auction_bidder.bidder_num exists.
     * We have constraint on (ab.auction_id, ab.bidder_num) unique column index, SAM-3286
     * We MUST search among active and deleted users,
     * because deleted user's bidder# can still exist in `auction_bidder` table.
     *
     * @param string $bidderNum bidder number to check
     * @param int|null $auctionId auction, where to check
     * @param int[] $skipUserIds user.id exclude user
     * @param int[] $skipAuctionBidderIds auction_bidder.id exclude records
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existBidderNo(
        int|string $bidderNum,
        ?int $auctionId = null,
        array $skipUserIds = [],
        array $skipAuctionBidderIds = [],
        bool $isReadOnlyDb = false
    ): bool {
        $bidderNumPad = $this->getBidderNumberPadding()->add($bidderNum);
        $auctionBidderRepository = $this->createAuctionBidderReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterBidderNum($bidderNumPad);
        if ($auctionId) {
            $auctionBidderRepository->filterAuctionId($auctionId);
        }
        if ($skipAuctionBidderIds) {
            $auctionBidderRepository->skipId($skipAuctionBidderIds);
        }
        if ($skipUserIds) {
            $auctionBidderRepository->skipUserId($skipUserIds);
        }
        $isFound = $auctionBidderRepository->exist();
        return $isFound;
    }

    /**
     * Check whether a user is registered for an auction
     *
     * @param int|null $userId
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isAuctionRegistered(?int $userId, ?int $auctionId, bool $isReadOnlyDb = false): bool
    {
        if (
            !$userId
            || !$auctionId
        ) {
            return false;
        }

        $isFound = $this->createAuctionBidderReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterUserId($userId)
            ->exist();
        return $isFound;
    }

    /**
     * Check whether a user is approved for an auction.
     *
     * @param int|null $userId - null/0 for anonymous user, results with false.
     * @param int|null $auctionId - null/0 when auction unknown, results with false.
     * @param array $optionals - Options and additional values that help to optimize detection logic = [
     *  'checkNaa' => bool      - should check No Auction Approvable (true by def.).
     *  'accountId' => int      - account id of auction (optimization preload).
     *  'bidderNum' => string   - bidder# of auction registered user (optimization preload).
     * ]
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isAuctionApproved(
        ?int $userId,
        ?int $auctionId,
        array $optionals = [],
        bool $isReadOnlyDb = false
    ): bool {
        $shouldCheckNaa = $optionals[self::OP_CHECK_NAA] ?? true;
        $accountId = $optionals[self::OP_ACCOUNT_ID] ?? null;
        $bidderNum = $optionals[self::OP_BIDDER_NUM] ?? null;

        if (!$userId || !$auctionId) {
            return false;
        }

        $userFlag = null;
        if ($shouldCheckNaa) {
            if (!$accountId) {
                $auction = $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb);
                if (!$auction) {
                    return false;
                }
                $accountId = $auction->AccountId;
            }
            $userFlag = $this->getUserFlagging()->detectFlag($userId, $accountId);
        }

        if ($bidderNum === null) {
            $auctionBidder = $this->getAuctionBidderLoader()->load($userId, $auctionId, $isReadOnlyDb);
            if (!$auctionBidder) {
                return false;
            }
            $bidderNum = (string)$auctionBidder->BidderNum;
        }

        $auctionBidderPureChecker = AuctionBidderPureChecker::new();
        $isApproved = $auctionBidderPureChecker->isApproved($shouldCheckNaa, $userFlag, $bidderNum);
        return $isApproved;
    }

    /**
     * Count number of registered bidders for an auction.
     * Doesn't take into account whether they are approved or even valid users.
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countRegisteredByAuctionId(int $auctionId, bool $isReadOnlyDb = false): int
    {
        $count = $this->createAuctionBidderReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->count();
        return $count;
    }

    /**
     * Check, if assigned users exist in auction - are registered, but may be not approved yet.
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existRegisteredByAuctionId(int $auctionId, bool $isReadOnlyDb = false): bool
    {
        $count = $this->countRegisteredByAuctionId($auctionId, $isReadOnlyDb);
        return $count > 0;
    }
}
