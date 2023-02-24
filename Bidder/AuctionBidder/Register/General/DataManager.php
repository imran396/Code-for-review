<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/1/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Register\General;

use Auction;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Load\Exception\CouldNotFindAuction;
use Sam\Bidder\Agent\Load\AgentDataLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Register\Config\ConfigAwareTrait;
use Sam\Bidder\AuctionBidder\Register\Load\DataLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Register\Log\LoggerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Flag\UserFlagPureDetector;
use Sam\Storage\Lock\DbLockerCreateTrait;
use Sam\User\Flag\UserFlaggingAwareTrait;
use Sam\User\Privilege\Validate\BidderPrivilegeCheckerAwareTrait;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;

/**
 * Class DataManager
 * @package Sam\Bidder\AuctionBidder\Register\General
 */
class DataManager extends CustomizableClass
{
    use AgentDataLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use BidderPrivilegeCheckerAwareTrait;
    use ConfigAwareTrait;
    use DataLoaderAwareTrait;
    use DbLockerCreateTrait;
    use LoggerAwareTrait;
    use RoleCheckerAwareTrait;
    use UserFlaggingAwareTrait;

    /** @var int[][] */
    protected array $registeringUserIds = [];
    /** @var string */
    protected string $lastErrorMessage = '';
    protected ?Auction $auction = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function getLastErrorMessage(): string
    {
        return $this->lastErrorMessage;
    }

    /**
     * Test existing lock or define it to avoid race condition (SAM-3951)
     * @return bool
     */
    public function checkAndGetLock(): bool
    {
        $this->lastErrorMessage = '';
        $lockTimeout = (int)ini_get('max_execution_time');
        $lockName = $this->buildLockName();
        $isFreeLock = $this->createDbLocker()->getLock($lockName, 5, $lockTimeout);
        if (!$isFreeLock) {
            $message = "Cannot lock registration process. Please, try again";
            $this->lastErrorMessage = $message;
            log_error($this->getLogger()->decorate($message));
            return false;
        }
        return true;
    }

    /**
     * Release lock, we set to avoid race condition (SAM-3951)
     * @return bool
     */
    public function releaseLock(): bool
    {
        $this->lastErrorMessage = '';
        $lockName = $this->buildLockName();
        $isLockReleased = $this->createDbLocker()->releaseLock($lockName);
        if (!$isLockReleased) {
            $message = "Lock ({$lockName}) was not released";
            $this->lastErrorMessage = $message;
            log_error($this->getLogger()->decorate($message));
            return false;
        }
        return true;
    }


    /**
     * Load, cache and return user ids for registration in auction.
     * Array contains current user and his buyers.
     * Buyers without bidder privilege are skipped.
     * @param int $userId
     * @return int[]
     */
    public function getRegisteringUserIds(int $userId): array
    {
        if (!isset($this->registeringUserIds[$userId])) {
            $this->registeringUserIds[$userId] = $this->findRegisteringUserIds($userId);
        }
        return $this->registeringUserIds[$userId];
    }

    public function loadAuction(): Auction
    {
        $auctionId = $this->getConfig()->getAuctionId();
        if ($this->auction === null) {
            $this->auction = $this->getAuctionLoader()->load($auctionId);
        }
        if ($this->auction === null) {
            throw CouldNotFindAuction::withId($auctionId);
        }
        return $this->auction;
    }

    /**
     * Load user ids for registration in auction.
     * Array contains current user and his buyers.
     * Buyers without bidder privilege are skipped.
     * @param int $generalUserId
     * @return int[]
     */
    protected function findRegisteringUserIds(int $generalUserId): array
    {
        $bidderUserIds = [];
        $shouldRegisterBuyers = $this->getConfig()->shouldRegisterBuyers();
        if ($shouldRegisterBuyers) {
            $hasPrivilegeForAgent = $this->getBidderPrivilegeChecker()
                ->initByUserId($generalUserId)
                ->hasPrivilegeForAgent();
            if ($hasPrivilegeForAgent) {
                $buyerUserIds = $this->getAgentDataLoader()->loadBuyersIds($generalUserId);
                $auction = $this->loadAuction();
                foreach ($buyerUserIds as $userId) {
                    $userFlag = $this->getUserFlagging()->detectFlag($userId, $auction->AccountId);
                    if (
                        $this->getRoleChecker()->isBidder($userId, true)
                        && UserFlagPureDetector::new()->isAuctionApprovalFlag($userFlag)
                    ) {
                        $bidderUserIds[] = $userId;
                    } else {
                        log_debug($this->getLogger()->decorate("User without bidder privilege skipped", $userId));
                    }
                }
            }
        }
        array_unshift($bidderUserIds, $generalUserId);
        return $bidderUserIds;
    }

    /**
     * @return string
     */
    protected function buildLockName(): string
    {
        $auction = $this->loadAuction();
        $auctionRows = $this->getDataLoader()->getRegisteringAuctionRows(
            $auction,
            $this->getConfig()->shouldRegisterInSaleGroup()
        );
        $firstAuctionId = $auctionRows[0]['id'];
        return sprintf(Constants\DbLock::MULTIPLE_AUCTION_BIDDER_REGISTRATION_BY_AUCTION_ID_TPL, $firstAuctionId);
    }
}
