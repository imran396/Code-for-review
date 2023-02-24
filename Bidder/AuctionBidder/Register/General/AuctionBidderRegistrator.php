<?php
/**
 * Main service of user registration in auction
 *
 * SAM-3904: Auction bidder registration class
 *
 * @author        Igors Kotlevskis
 * @since         Sep 14, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 * Usage example:
 * $registrator = Registrator::new()
 *     ->setUser(..)
 *     ->setAuction(..)
 *     ->enableAutoApprove(..)
 *     ->setCarrierMethod(..)
 *     ->skipAuthOrCapture(..);
 * $auctionBidder = $registrator->register();
 * if (!$auctionBidder) {
 *     $registrator->getErrorMessage();
 * }
 */

namespace Sam\Bidder\AuctionBidder\Register\General;

use AuctionBidder;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Bidder\Agent\Load\AgentDataLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Register\Config\ConfigAwareTrait;
use Sam\Bidder\AuctionBidder\Register\Load\DataLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Register\Log\LoggerAwareTrait;
use Sam\Bidder\AuctionBidder\Register\Notify\NotifierAwareTrait;
use Sam\Bidder\AuctionBidder\Register\Single\SingleUserRegistrator;
use Sam\User\Privilege\Validate\BidderPrivilegeCheckerAwareTrait;
use Sam\User\Validate\UserExistenceCheckerAwareTrait;

/**
 * Class Registrator
 * @package Sam\Bidder\AuctionBidder\Register
 */
class AuctionBidderRegistrator extends CustomizableClass
{
    use AgentDataLoaderAwareTrait;
    use AuctionBidderCheckerAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use BidderNumPaddingAwareTrait;
    use BidderPrivilegeCheckerAwareTrait;
    use ConfigAwareTrait;
    use DataLoaderAwareTrait;
    use LoggerAwareTrait;
    use NotifierAwareTrait;
    use UserExistenceCheckerAwareTrait;

    protected ?DataManager $dataManager = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Register a user for an auction
     * Also register all buyers of user if user is agent
     *
     * @return AuctionBidder|null
     */
    public function register(): ?AuctionBidder
    {
        $auctionId = $this->getConfig()->getAuctionId();
        $userId = $this->getConfig()->getUserId();

        $this->getLogger()
            ->setAuctionId($auctionId)
            ->setUserId($userId);

        log_debug($this->getLogger()->decorate("Process started"));

        if (!$this->validateGeneralUser()) {
            return null;
        }

        $userIds = $this->getDataManager()->getRegisteringUserIds($userId);
        if (count($userIds) > 1) {
            $message = sprintf("User is Agent and has %d buyer(s)", count($userIds) - 1);
            log_debug($this->getLogger()->decorate($message));
        }

        // Lock registration process for all agent's buyers and for all auctions in sale group
        if (!$this->getDataManager()->checkAndGetLock()) {
            $this->getConfig()->setErrorMessage($this->getDataManager()->getLastErrorMessage());
            return null;
        }

        $this->registerUsers();

        // Unlock registration process
        $this->getDataManager()->releaseLock();
        // Notifications processing extracted outside locked process
        if ($this->getConfig()->isEmailNotification()) {
            $this->getNotifier()->callNotifications($auctionId, $this->getConfig()->getEditorUserId());
        }
        // Return AuctionBidder record of initially registered user
        $auctionBidder = $this->getAuctionBidderLoader()->load($userId, $auctionId);
        log_debug($this->getLogger()->decorate("Successfully finished"));
        return $auctionBidder;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->getConfig()->getErrorMessage();
    }

    /**
     * Initial user validation.
     * When it is failed, we should not continue registration of his buyers.
     * @return bool
     */
    protected function validateGeneralUser(): bool
    {
        if (!$this->isAllowedRegistrationWithoutBidderRole()) {
            return false;
        }

        if (!$this->isAllowedRegistrationOfFlaggedUser()) {
            return false;
        }

        if (!$this->isAllowedAssignBidderNumber()) {
            return false;
        }

        return true;
    }

    protected function registerUsers(): void
    {
        $userIds = $this->getDataManager()->getRegisteringUserIds($this->getConfig()->getUserId());
        $auctionId = $this->getConfig()->getAuctionId();
        foreach ($userIds as $userId) {
            $singleUserRegistrator = $this->createSingleUserRegistrator()
                ->construct($userId, $auctionId);
            $singleUserRegistrator->register();
        }
    }

    /**
     * @return SingleUserRegistrator
     */
    protected function createSingleUserRegistrator(): SingleUserRegistrator
    {
        $singleUserRegistrator = SingleUserRegistrator::new()
            ->setConfig($this->getConfig())
            ->setDataLoader($this->getDataLoader())
            ->setNotifier($this->getNotifier());
        return $singleUserRegistrator;
    }

    /**
     * @return DataManager
     */
    protected function getDataManager(): DataManager
    {
        if ($this->dataManager === null) {
            $this->dataManager = DataManager::new()
                ->setConfig($this->getConfig())
                ->setDataLoader($this->getDataLoader())
                ->setLogger($this->getLogger());
        }
        return $this->dataManager;
    }

    /**
     * @param DataManager $dataManager
     * @return static
     */
    public function setDataManager(DataManager $dataManager): static
    {
        $this->dataManager = $dataManager;
        return $this;
    }

    /**
     * Check, if we permit to register user according to constraint of Bidder Role
     * @return bool
     */
    protected function isAllowedRegistrationWithoutBidderRole(): bool
    {
        if (!$this->getConfig()->isRestrictionByBidderRole()) {
            return true;
        }

        $isBidder = $this->getBidderPrivilegeChecker()
            ->initByUserId($this->getConfig()->getUserId())
            ->isBidder();
        if (!$isBidder) {
            $message = "User without bidder role is restricted from auction registration";
            $this->getConfig()->setErrorMessage($message);
            log_debug($this->getLogger()->decorate($message));
            return false;
        }

        return true;
    }

    /**
     * Check, if we permit to register user according to constraint based on his flag
     * @return bool
     */
    protected function isAllowedRegistrationOfFlaggedUser(): bool
    {
        if (!$this->getConfig()->isRestrictionByUserFlag()) {
            // Restriction by user's flag is not required
            return true;
        }

        $isApprovableUser = $this->getDataLoader()->isApprovableUser(
            $this->getConfig()->getUserId(),
            $this->getConfig()->getAuctionId()
        );
        if (!$isApprovableUser) {
            $message = "Flagged user is restricted from auction registration";
            $this->getConfig()->setErrorMessage($message);
            log_debug($this->getLogger()->decorate($message));
            return false;
        }

        return true;
    }

    /**
     * Check, if passed bidder# can be assigned to user.
     * @return bool
     */
    protected function isAllowedAssignBidderNumber(): bool
    {
        $userId = $this->getConfig()->getUserId();
        $auctionId = $this->getConfig()->getAuctionId();
        $bidderNumber = $this->getConfig()->getBidderNumber();

        if ($this->getBidderNumberPadding()->isNone($bidderNumber)) {
            return true;
        }

        if ($this->getAuctionBidderChecker()->existBidderNo($bidderNumber, $auctionId, [$userId])) {
            $message = "Failed to assign bidder number. Bidder number {$bidderNumber} exist.";
            $this->getConfig()->setErrorMessage($message);
            log_debug($message);
            return false;
        }

        /**
         * Check if bidder# is already assigned as a permanent customer# to a different user.
         * Customer# can be numeric only.
         */
        if (is_numeric($bidderNumber)) {
            if ($this->getUserExistenceChecker()->existByCustomerNoAmongPermanent((int)$bidderNumber, [$userId])) {
                $message = "Failed to assign bidder number. Bidder number {$bidderNumber} is already assigned as permanent customer# to a different user.";
                $this->getConfig()->setErrorMessage($message);
                log_debug($message);
                return false;
            }
        }

        return true;
    }
}
