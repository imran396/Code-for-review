<?php
/**
 * SAM-5041: Soap API RegisterBidder improvement
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\BidderNum\ChangeExisting\Internal\Load;

use AuctionBidder;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Flag\UserFlaggingAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Validate\UserExistenceCheckerAwareTrait;
use User;

/**
 * Class DataProvider
 * @package Sam\Bidder\BidderNum\ChangeExisting\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use AuctionBidderCheckerAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use UserExistenceCheckerAwareTrait;
    use UserFlaggingAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadAuctionBidder(int $userId, int $auctionId, bool $isReadOnlyDb = false): ?AuctionBidder
    {
        return $this->getAuctionBidderLoader()->load($userId, $auctionId, $isReadOnlyDb);
    }

    public function existBidderNo(string $bidderNumber, int $auctionId, int $skipUserId, bool $isReadOnlyDb = false): bool
    {
        return $this->getAuctionBidderChecker()->existBidderNo($bidderNumber, $auctionId, [$skipUserId], [], $isReadOnlyDb);
    }

    public function existByCustomerNoAmongPermanent(int $bidderNumber, array $skipUserIds, bool $isReadOnlyDb = false): bool
    {
        return $this->getUserExistenceChecker()->existByCustomerNoAmongPermanent($bidderNumber, $skipUserIds, $isReadOnlyDb);
    }

    public function loadUser(int $userId, bool $isReadOnlyDb = false): ?User
    {
        return $this->getUserLoader()->load($userId, $isReadOnlyDb);
    }

    public function loadAuctionAccountId(int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        $row = $this->getAuctionLoader()->loadSelected(['account_id'], $auctionId, $isReadOnlyDb);
        $accountId = Cast::toInt($row['account_id'] ?? null);
        return $accountId;
    }

    public function isUserFlagged(int $userId, int $accountId, bool $isReadOnlyDb = false): bool
    {
        return $this->getUserFlagging()->isAuctionApprovalByUserId($userId, $accountId, $isReadOnlyDb);
    }
}
