<?php
/** @noinspection PhpUnused */

/**
 * SAM-4819: Entity aware traits
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/14/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\AwareTrait;

use Admin;
use Bidder;
use Consignor;
use Sam\Storage\Entity\Aggregate\UserAggregate;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;
use Sam\User\Privilege\Validate\BidderPrivilegeChecker;
use Sam\User\Privilege\Validate\ConsignorPrivilegeChecker;
use User;
use UserAuthentication;
use UserBilling;
use UserInfo;
use UserLog;
use UserShipping;

/**
 * Trait UserAwareTrait
 * @package Sam\Storage\Entity\AwareTrait
 */
trait UserAwareTrait
{
    protected ?UserAggregate $userAggregate = null;

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->getUserAggregate()->getUserId();
    }

    /**
     * @param int|null $userId
     * @return static
     */
    public function setUserId(?int $userId): static
    {
        $this->getUserAggregate()->setUserId($userId);
        return $this;
    }

    /**
     * Update value of $userId by id of $user entity
     * @return $this
     */
    public function refreshUserId(): static
    {
        $this->getUserAggregate()->refreshUserId();
        return $this;
    }

    /**
     * @return bool
     */
    public function hasUser(): bool
    {
        return $this->getUserAggregate()->hasUser();
    }

    /**
     * @param bool $isReadOnlyDb
     * @return User|null
     */
    public function getUser(bool $isReadOnlyDb = false): ?User
    {
        return $this->getUserAggregate()->getUser($isReadOnlyDb);
    }

    /**
     * Return User object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return User
     */
    public function getUserOrCreate(bool $isReadOnlyDb = false): User
    {
        return $this->getUserAggregate()->getUserOrCreate($isReadOnlyDb);
    }

    /**
     * Return User object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return User
     */
    public function getUserOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): User
    {
        return $this->getUserAggregate()->getUserOrCreatePersisted($editorUserId, $isReadOnlyDb);
    }

    /**
     * @param User|null $user
     * @return static
     */
    public function setUser(?User $user): static
    {
        $this->getUserAggregate()->setUser($user);
        return $this;
    }

    // --- User Info ---

    /**
     * @param bool $isReadOnlyDb
     * @return UserInfo|null
     */
    public function getUserInfo(bool $isReadOnlyDb = false): ?UserInfo
    {
        return $this->getUserAggregate()->getUserInfo($isReadOnlyDb);
    }

    /**
     * Return UserInfo object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return UserInfo
     */
    public function getUserInfoOrCreate(bool $isReadOnlyDb = false): UserInfo
    {
        return $this->getUserAggregate()->getUserInfoOrCreate($isReadOnlyDb);
    }

    /**
     * Return UserInfo object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return UserInfo
     */
    public function getUserInfoOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): UserInfo
    {
        return $this->getUserAggregate()->getUserInfoOrCreatePersisted($editorUserId, $isReadOnlyDb);
    }

    /**
     * Set UserInfo object
     * @param UserInfo|null $userInfo
     * @return static
     */
    public function setUserInfo(?UserInfo $userInfo): static
    {
        $this->getUserAggregate()->setUserInfo($userInfo);
        return $this;
    }

    // --- User Billing ---

    /**
     * @param bool $isReadOnlyDb
     * @return UserBilling|null
     */
    public function getUserBilling(bool $isReadOnlyDb = false): ?UserBilling
    {
        return $this->getUserAggregate()->getUserBilling($isReadOnlyDb);
    }

    /**
     * Return UserBilling object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return UserBilling
     */
    public function getUserBillingOrCreate(bool $isReadOnlyDb = false): UserBilling
    {
        return $this->getUserAggregate()->getUserBillingOrCreate($isReadOnlyDb);
    }

    /**
     * Return UserBilling object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return UserBilling
     */
    public function getUserBillingOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): UserBilling
    {
        return $this->getUserAggregate()->getUserBillingOrCreatePersisted($editorUserId, $isReadOnlyDb);
    }

    /**
     * Set UserBilling object
     * @param UserBilling|null $userBilling
     * @return static
     */
    public function setUserBilling(?UserBilling $userBilling): static
    {
        $this->getUserAggregate()->setUserBilling($userBilling);
        return $this;
    }

    // --- User Log ---

    /**
     * @return UserLog
     */
    public function createUserLog(): UserLog
    {
        return $this->getUserAggregate()->createUserLog();
    }

    /**
     * @return UserLog|null
     */
    public function getUserLog(): ?UserLog
    {
        return $this->getUserAggregate()->getUserLog();
    }

    /**
     * Set UserLog object
     * @param UserLog|null $userLog
     * @return static
     */
    public function setUserLog(?UserLog $userLog): static
    {
        $this->getUserAggregate()->setUserLog($userLog);
        return $this;
    }

    // --- User Shipping ---

    /**
     * Return UserShipping object
     * @param bool $isReadOnlyDb
     * @return UserShipping|null
     */
    public function getUserShipping(bool $isReadOnlyDb = false): ?UserShipping
    {
        return $this->getUserAggregate()->getUserShipping($isReadOnlyDb);
    }

    /**
     * Return UserShipping object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return UserShipping
     */
    public function getUserShippingOrCreate(bool $isReadOnlyDb = false): UserShipping
    {
        return $this->getUserAggregate()->getUserShippingOrCreate($isReadOnlyDb);
    }

    /**
     * Return UserShipping object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return UserShipping
     */
    public function getUserShippingOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): UserShipping
    {
        return $this->getUserAggregate()->getUserShippingOrCreatePersisted($editorUserId, $isReadOnlyDb);
    }

    /**
     * Set UserShipping object
     * @param UserShipping|null $userShipping
     * @return static
     */
    public function setUserShipping(?UserShipping $userShipping): static
    {
        $this->getUserAggregate()->setUserShipping($userShipping);
        return $this;
    }

    // --- User Authentication ---

    /**
     * @param bool $isReadOnlyDb
     * @return UserAuthentication|null
     */
    public function getUserAuthentication(bool $isReadOnlyDb = false): ?UserAuthentication
    {
        return $this->getUserAggregate()->getUserAuthentication($isReadOnlyDb);
    }

    /**
     * Return UserAuthentication object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return UserAuthentication
     */
    public function getUserAuthenticationOrCreate(bool $isReadOnlyDb = false): UserAuthentication
    {
        return $this->getUserAggregate()->getUserAuthenticationOrCreate($isReadOnlyDb);
    }

    /**
     * Return UserAuthentication object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return UserAuthentication
     */
    public function getUserAuthenticationOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): UserAuthentication
    {
        return $this->getUserAggregate()->getUserAuthenticationOrCreatePersisted($isReadOnlyDb, $editorUserId);
    }

    /**
     * Set UserAuthentication object
     * @param UserAuthentication|null $userAuthentication
     * @return static
     */
    public function setUserAuthentication(?UserAuthentication $userAuthentication): static
    {
        $this->getUserAggregate()->setUserAuthentication($userAuthentication);
        return $this;
    }

    // --- Admin ---

    /**
     * @param bool $isReadOnlyDb
     * @return Admin|null
     */
    public function getAdmin(bool $isReadOnlyDb = false): ?Admin
    {
        return $this->getUserAggregate()->getAdmin($isReadOnlyDb);
    }

    /**
     * Return Admin object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return Admin
     */
    public function getAdminOrCreate(bool $isReadOnlyDb = false): Admin
    {
        return $this->getUserAggregate()->getAdminOrCreate($isReadOnlyDb);
    }

    /**
     * Return Admin object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return Admin
     */
    public function getAdminOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): Admin
    {
        return $this->getUserAggregate()->getAdminOrCreatePersisted($editorUserId, $isReadOnlyDb);
    }

    /**
     * Set Admin object
     * @param Admin|null $admin
     * @return static
     */
    public function setAdmin(?Admin $admin): static
    {
        $this->getUserAggregate()->setAdmin($admin);
        return $this;
    }

    // --- Bidder ---

    /**
     * @param bool $isReadOnlyDb
     * @return Bidder|null
     */
    public function getBidder(bool $isReadOnlyDb = false): ?Bidder
    {
        return $this->getUserAggregate()->getBidder($isReadOnlyDb);
    }

    /**
     * Return Bidder object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return Bidder
     */
    public function getBidderOrCreate(bool $isReadOnlyDb = false): Bidder
    {
        return $this->getUserAggregate()->getBidderOrCreate($isReadOnlyDb);
    }

    /**
     * Return Bidder object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return Bidder
     */
    public function getBidderOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): Bidder
    {
        return $this->getUserAggregate()->getBidderOrCreatePersisted($editorUserId, $isReadOnlyDb);
    }

    /**
     * Set Bidder object
     * @param Bidder|null $bidder
     * @return static
     */
    public function setBidder(?Bidder $bidder): static
    {
        $this->getUserAggregate()->setBidder($bidder);
        return $this;
    }

    // --- Consignor ---

    /**
     * @param bool $isReadOnlyDb
     * @return Consignor|null
     */
    public function getConsignor(bool $isReadOnlyDb = false): ?Consignor
    {
        return $this->getUserAggregate()->getConsignor($isReadOnlyDb);
    }

    /**
     * Return Consignor object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return Consignor
     */
    public function getConsignorOrCreate(bool $isReadOnlyDb = false): Consignor
    {
        return $this->getUserAggregate()->getConsignorOrCreate($isReadOnlyDb);
    }

    /**
     * Return Consignor object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return Consignor
     */
    public function getConsignorOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): Consignor
    {
        return $this->getUserAggregate()->getConsignorOrCreatePersisted($editorUserId, $isReadOnlyDb);
    }

    /**
     * Set Consignor object
     * @param Consignor|null $consignor
     * @return static
     */
    public function setConsignor(?Consignor $consignor): static
    {
        $this->getUserAggregate()->setConsignor($consignor);
        return $this;
    }

    // --- Privilege checking methods ---

    /**
     * @return bool
     */
    public function hasAdminRole(): bool
    {
        return $this->getUserAggregate()->hasAdminRole();
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableAdminRole(bool $enable): static
    {
        $this->getUserAggregate()->enableAdminRole($enable);
        return $this;
    }

    /**
     * @return bool
     */
    public function hasBidderRole(): bool
    {
        return $this->getUserAggregate()->hasBidderRole();
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableBidderRole(bool $enable): static
    {
        $this->getUserAggregate()->enableBidderRole($enable);
        return $this;
    }

    /**
     * @return bool
     */
    public function hasConsignorRole(): bool
    {
        return $this->getUserAggregate()->hasConsignorRole();
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableConsignorRole(bool $enable): static
    {
        $this->getUserAggregate()->enableConsignorRole($enable);
        return $this;
    }

    /**
     * @return AdminPrivilegeChecker
     */
    public function getUserAdminPrivilegeChecker(): AdminPrivilegeChecker
    {
        return $this->getUserAggregate()->getAdminPrivilegeChecker();
    }

    /**
     * @param AdminPrivilegeChecker $adminPrivilegeChecker
     */
    public function setUserAdminPrivilegeChecker(AdminPrivilegeChecker $adminPrivilegeChecker): void
    {
        $this->getUserAggregate()->setAdminPrivilegeChecker($adminPrivilegeChecker);
    }

    /**
     * @return BidderPrivilegeChecker
     */
    public function getUserBidderPrivilegeChecker(): BidderPrivilegeChecker
    {
        return $this->getUserAggregate()->getBidderPrivilegeChecker();
    }

    /**
     * @param BidderPrivilegeChecker $bidderPrivilegeChecker
     */
    public function setUserBidderPrivilegeChecker(BidderPrivilegeChecker $bidderPrivilegeChecker): void
    {
        $this->getUserAggregate()->setBidderPrivilegeChecker($bidderPrivilegeChecker);
    }

    /**
     * @return ConsignorPrivilegeChecker
     */
    public function getUserConsignorPrivilegeChecker(): ConsignorPrivilegeChecker
    {
        return $this->getUserAggregate()->getConsignorPrivilegeChecker();
    }

    /**
     * @param ConsignorPrivilegeChecker $consignorPrivilegeChecker
     */
    public function setUserConsignorPrivilegeChecker(ConsignorPrivilegeChecker $consignorPrivilegeChecker): void
    {
        $this->getUserAggregate()->setConsignorPrivilegeChecker($consignorPrivilegeChecker);
    }

    // --- UserAggregate ---

    /**
     * I make it public to be accessible via unit test
     * @return UserAggregate
     */
    public function getUserAggregate(): UserAggregate
    {
        if ($this->userAggregate === null) {
            $this->userAggregate = UserAggregate::new();
        }
        return $this->userAggregate;
    }

    /**
     * @param UserAggregate $userAggregate
     * @return static
     */
    public function setUserAggregate(UserAggregate $userAggregate): static
    {
        $this->userAggregate = $userAggregate;
        return $this;
    }
}
