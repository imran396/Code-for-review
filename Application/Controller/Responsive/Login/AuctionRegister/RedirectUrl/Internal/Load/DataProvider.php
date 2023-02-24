<?php
/**
 * SAM-11674: Ability to adjust public page routing. Prepare existing routes
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 08, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Login\AuctionRegister\RedirectUrl\Internal\Load;

use Auction;
use AuctionBidderOptionSelection;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\AdditionalRegistrationOption\Load\AdditionalRegistrationOptionLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Privilege\Validate\BidderPrivilegeCheckerAwareTrait;
use Sam\User\Validate\UserBillingCheckerAwareTrait;

/**
 * Class DataProvider
 * @package
 */
class DataProvider extends CustomizableClass
{
    use AdditionalRegistrationOptionLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionBidderCheckerAwareTrait;
    use BidderPrivilegeCheckerAwareTrait;
    use UserBillingCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadAuction(?int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb);
    }

    public function isAuctionRegistered(?int $userId, ?int $auctionId, bool $isReadOnlyDb = false): bool
    {
        return $this->getAuctionBidderChecker()->isAuctionRegistered($userId, $auctionId, $isReadOnlyDb);
    }

    public function loadAdditionalRegistrationOptionsByAccount(int $accountId, bool $isReadOnlyDb = false): array
    {
        return $this->getAdditionalRegistrationOptionLoader()->loadByAccount($accountId, $isReadOnlyDb);;
    }

    public function hasReadyCcTransactionInfo(int $userId, int $accountId): bool
    {
        return $this->getUserBillingChecker()->hasReadyCcTransactionInfo($userId, $accountId);;
    }

    public function loadAdditionalRegistrationOptionsByUserAndAuction(?int $userId, ?int $auctionId, bool $isReadOnlyDb = false): ?AuctionBidderOptionSelection
    {
        return $this->getAdditionalRegistrationOptionLoader()
            ->setUserId($userId)
            ->setAuctionId($auctionId)
            ->loadByUserAndAuction($isReadOnlyDb);
    }

    public function isBidder(?int $userId, bool $isReadOnlyDb = false): bool
    {
        return $this->getBidderPrivilegeChecker()
            ->initByUserId($userId)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->isBidder();
    }

    public function hasPrivilegeForPreferred(?int $userId, bool $isReadOnlyDb = false): bool
    {
        return $this->getBidderPrivilegeChecker()
            ->initByUserId($userId)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->hasPrivilegeForPreferred();
    }
}
