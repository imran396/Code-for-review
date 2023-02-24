<?php
/**
 * SAM-4819: Entity aware traits
 *
 * Aggregate class can be used, when we need to operate we several User entities in one class namespace.
 * We can create concrete sense aggregate aware traits, eg. EditorUserAggregateAwareTrait
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\Aggregate;

use Admin;
use Bidder;
use Consignor;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\Admin\AdminWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\Bidder\BidderWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\Consignor\ConsignorWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\User\UserWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserAuthentication\UserAuthenticationWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserBilling\UserBillingWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserInfo\UserInfoWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserShipping\UserShippingWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;
use Sam\User\Privilege\Validate\BidderPrivilegeChecker;
use Sam\User\Privilege\Validate\ConsignorPrivilegeChecker;
use Sam\User\Privilege\Validate\RoleChecker;
use User;
use UserAuthentication;
use UserBilling;
use UserInfo;
use UserLog;
use UserShipping;

/**
 * Class UserAggregate
 * @package Sam\Storage\Entity\Aggregate
 */
class UserAggregate extends EntityAggregateBase
{
    use AdminWriteRepositoryAwareTrait;
    use BidderWriteRepositoryAwareTrait;
    use ConsignorWriteRepositoryAwareTrait;
    use EntityFactoryCreateTrait;
    use UserAuthenticationWriteRepositoryAwareTrait;
    use UserBillingWriteRepositoryAwareTrait;
    use UserInfoWriteRepositoryAwareTrait;
    use UserLoaderAwareTrait;
    use UserShippingWriteRepositoryAwareTrait;
    use UserWriteRepositoryAwareTrait;

    private ?int $userId = null;
    private ?User $user = null;
    private ?UserInfo $userInfo = null;
    private ?UserBilling $userBilling = null;
    private ?UserLog $userLog = null;
    private ?UserShipping $userShipping = null;
    private ?UserAuthentication $userAuthentication = null;
    private ?Admin $admin = null;
    private ?Bidder $bidder = null;
    private ?Consignor $consignor = null;
    private ?bool $hasAdminRole = null;
    private ?bool $hasBidderRole = null;
    private ?bool $hasConsignorRole = null;
    protected ?AdminPrivilegeChecker $adminPrivilegeChecker = null;
    protected ?BidderPrivilegeChecker $bidderPrivilegeChecker = null;
    protected ?ConsignorPrivilegeChecker $consignorPrivilegeChecker = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Empty aggregated entities
     * @return static
     */
    public function clear(): static
    {
        $this->admin = null;
        $this->adminPrivilegeChecker = null;
        $this->bidder = null;
        $this->bidderPrivilegeChecker = null;
        $this->consignor = null;
        $this->consignorPrivilegeChecker = null;
        $this->hasAdminRole = null;
        $this->hasBidderRole = null;
        $this->hasConsignorRole = null;
        $this->user = null;
        $this->userAuthentication = null;
        $this->userBilling = null;
        $this->userId = null;
        $this->userInfo = null;
        $this->userShipping = null;
        return $this;
    }

    // --- user.id ---

    /**
     * Return Id of User
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * Set Id of User
     * @param int|null $userId
     * @return static
     */
    public function setUserId(?int $userId): static
    {
        $userId = (int)$userId ?: null;
        if ($this->userId !== $userId) {
            $this->clear();
        }
        $this->userId = $userId;
        return $this;
    }

    /**
     * Update value of $userId by id of $user entity
     * @return $this
     */
    public function refreshUserId(): static
    {
        $this->userId = $this->user->Id ?? null;
        return $this;
    }

    // --- User ---

    /**
     * @return bool
     */
    public function hasUser(): bool
    {
        return ($this->user !== null);
    }

    /**
     * Return User object
     * @param bool $isReadOnlyDb
     * @return User|null
     */
    public function getUser(bool $isReadOnlyDb = false): ?User
    {
        if (
            $this->user === null
            && $this->userId > 0
        ) {
            $user = $this->getUserLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->load($this->userId, $isReadOnlyDb);
            // We want to drop $userId, if record not found by id
            $this->setUser($user);
        }
        return $this->user;
    }

    /**
     * Return User object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return User
     */
    public function getUserOrCreate(bool $isReadOnlyDb = false): User
    {
        $this->user = $this->getUser($isReadOnlyDb);
        if ($this->user === null) {
            $this->user = $this->createEntityFactory()->user();
        }
        return $this->user;
    }

    /**
     * Return User object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return User
     */
    public function getUserOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): User
    {
        $this->user = $this->getUserOrCreate($isReadOnlyDb);
        if (!$this->user->Id) {
            $this->getUserWriteRepository()->saveWithModifier($this->user, $editorUserId);
        }
        return $this->user;
    }

    /**
     * Set User entity.
     * It is general entity of aggregate, hence we clear all aggregated entities, when none passed.
     * Such behavior is not actual for related entities in aggregate.
     * @param User|null $user
     * @return static
     */
    public function setUser(?User $user): static
    {
        if (!$user) {
            $this->clear();
        } elseif ($user->Id !== $this->userId) {
            $this->clear();
            $this->userId = $user->Id;
        }
        $this->user = $user;
        return $this;
    }

    // --- User Info ---

    /**
     * @return bool
     */
    public function hasUserInfo(): bool
    {
        return $this->userInfo !== null;
    }

    /**
     * Return UserInfo object
     * @param bool $isReadOnlyDb
     * @return UserInfo|null
     */
    public function getUserInfo(bool $isReadOnlyDb = false): ?UserInfo
    {
        if (
            $this->userInfo === null
            && $this->userId > 0
        ) {
            $this->userInfo = $this->getUserLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadUserInfo($this->userId, $isReadOnlyDb);
        }
        return $this->userInfo;
    }

    /**
     * Return UserInfo object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return UserInfo
     */
    public function getUserInfoOrCreate(bool $isReadOnlyDb = false): UserInfo
    {
        $this->userInfo = $this->getUserInfo($isReadOnlyDb);
        if ($this->userInfo === null) {
            $this->userInfo = $this->createEntityFactory()->userInfo();
            $this->userInfo->UserId = $this->userId;
        }
        return $this->userInfo;
    }

    /**
     * Return UserInfo object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return UserInfo
     */
    public function getUserInfoOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): UserInfo
    {
        $this->userInfo = $this->getUserInfoOrCreate($isReadOnlyDb);
        if (!$this->userInfo->Id) {
            $this->getUserInfoWriteRepository()->saveWithModifier($this->userInfo, $editorUserId);
        }
        return $this->userInfo;
    }

    /**
     * Set UserInfo object
     * @param UserInfo|null $userInfo
     * @return static
     */
    public function setUserInfo(?UserInfo $userInfo = null): static
    {
        if (
            $userInfo
            && $userInfo->UserId !== $this->userId
        ) {
            $this->clear();
            $this->userId = $userInfo->UserId;
        }
        $this->userInfo = $userInfo;
        return $this;
    }

    //  --- User Billing ---

    /**
     * @return bool
     */
    public function hasUserBilling(): bool
    {
        return ($this->userBilling !== null);
    }

    /**
     * Return UserBilling object
     * @param bool $isReadOnlyDb
     * @return UserBilling|null
     */
    public function getUserBilling(bool $isReadOnlyDb = false): ?UserBilling
    {
        if (
            $this->userBilling === null
            && $this->userId > 0
        ) {
            $this->userBilling = $this->getUserLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadUserBilling($this->userId, $isReadOnlyDb);
        }
        return $this->userBilling;
    }

    /**
     * Return UserBilling object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return UserBilling
     */
    public function getUserBillingOrCreate(bool $isReadOnlyDb = false): UserBilling
    {
        $this->userBilling = $this->getUserBilling($isReadOnlyDb);
        if ($this->userBilling === null) {
            $this->userBilling = $this->createEntityFactory()->userBilling();
            $this->userBilling->UserId = $this->userId;
        }
        return $this->userBilling;
    }

    /**
     * Return UserBilling object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return UserBilling
     */
    public function getUserBillingOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): UserBilling
    {
        $this->userBilling = $this->getUserBillingOrCreate($isReadOnlyDb);
        if (!$this->userBilling->Id) {
            $this->getUserBillingWriteRepository()->saveWithModifier($this->userBilling, $editorUserId);
        }
        return $this->userBilling;
    }

    /**
     * Set UserBilling object
     * @param UserBilling|null $userBilling
     * @return static
     */
    public function setUserBilling(?UserBilling $userBilling = null): static
    {
        if (
            $userBilling
            && $userBilling->UserId !== $this->userId
        ) {
            $this->clear();
            $this->userId = $userBilling->UserId;
        }
        $this->userBilling = $userBilling;
        return $this;
    }

    //  --- User Log ---

    /**
     * @return bool
     */
    public function hasUserLog(): bool
    {
        return ($this->userLog !== null);
    }

    /**
     * Get UserLog object
     * @return UserLog|null
     */
    public function getUserLog(): ?UserLog
    {
        return $this->userLog;
    }

    /**
     * Create UserLog object
     * @return UserLog
     */
    public function createUserLog(): UserLog
    {
        $this->userLog = $this->createEntityFactory()->userLog();
        $this->userLog->UserId = $this->userId;
        return $this->userLog;
    }

    /**
     * Set UserLog object
     * @param UserLog|null $userLog
     * @return static
     */
    public function setUserLog(?UserLog $userLog = null): static
    {
        if (
            $userLog
            && $userLog->UserId !== $this->userId
        ) {
            $this->clear();
            $this->userId = $userLog->UserId;
        }
        $this->userLog = $userLog;
        return $this;
    }

    //  --- User Shipping ---

    /**
     * @return bool
     */
    public function hasUserShipping(): bool
    {
        return ($this->userShipping !== null);
    }

    /**
     * Return UserShipping object
     * @param bool $isReadOnlyDb
     * @return UserShipping|null
     */
    public function getUserShipping(bool $isReadOnlyDb = false): ?UserShipping
    {
        if (
            $this->userShipping === null
            && $this->userId > 0
        ) {
            $this->userShipping = $this->getUserLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadUserShipping($this->userId, $isReadOnlyDb);
        }
        return $this->userShipping;
    }

    /**
     * Return UserShipping object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return UserShipping
     */
    public function getUserShippingOrCreate(bool $isReadOnlyDb = false): UserShipping
    {
        $this->userShipping = $this->getUserShipping($isReadOnlyDb);
        if ($this->userShipping === null) {
            $this->userShipping = $this->createEntityFactory()->userShipping();
            $this->userShipping->UserId = $this->userId;
        }
        return $this->userShipping;
    }

    /**
     * Return UserShipping object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return UserShipping
     */
    public function getUserShippingOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): UserShipping
    {
        $this->userShipping = $this->getUserShippingOrCreate($isReadOnlyDb);
        if (!$this->userShipping->Id) {
            $this->getUserShippingWriteRepository()->saveWithModifier($this->userShipping, $editorUserId);
        }
        return $this->userShipping;
    }

    /**
     * Set UserShipping object
     * @param UserShipping|null $userShipping
     * @return static
     */
    public function setUserShipping(?UserShipping $userShipping = null): static
    {
        if (
            $userShipping
            && $userShipping->UserId !== $this->userId
        ) {
            $this->clear();
            $this->userId = $userShipping->UserId;
        }
        $this->userShipping = $userShipping;
        return $this;
    }

    //  --- User Authentication ---

    /**
     * @return bool
     */
    public function hasUserAuthentication(): bool
    {
        return ($this->userAuthentication !== null);
    }

    /**
     * Return UserAuthentication object
     * @param bool $isReadOnlyDb
     * @return UserAuthentication|null
     */
    public function getUserAuthentication(bool $isReadOnlyDb = false): ?UserAuthentication
    {
        if (
            $this->userAuthentication === null
            && $this->userId > 0
        ) {
            $this->userAuthentication = $this->getUserLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadUserAuthentication($this->userId, $isReadOnlyDb);
        }
        return $this->userAuthentication;
    }

    /**
     * Return UserAuthentication object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return UserAuthentication
     */
    public function getUserAuthenticationOrCreate(bool $isReadOnlyDb = false): UserAuthentication
    {
        $this->userAuthentication = $this->getUserAuthentication($isReadOnlyDb);
        if ($this->userAuthentication === null) {
            $this->userAuthentication = $this->createEntityFactory()->userAuthentication();
            $this->userAuthentication->UserId = $this->userId;
        }
        return $this->userAuthentication;
    }

    /**
     * Return UserAuthentication object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return UserAuthentication
     */
    public function getUserAuthenticationOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): UserAuthentication
    {
        $this->userAuthentication = $this->getUserAuthenticationOrCreate($isReadOnlyDb);
        if (!$this->userAuthentication->__Restored) {
            $this->getUserAuthenticationWriteRepository()->saveWithModifier($this->userAuthentication, $editorUserId);
        }
        return $this->userAuthentication;
    }

    /**
     * Set UserAuthentication object
     * @param UserAuthentication|null $userAuthentication
     * @return static
     */
    public function setUserAuthentication(?UserAuthentication $userAuthentication = null): static
    {
        if (
            $userAuthentication
            && $userAuthentication->UserId !== $this->userId
        ) {
            $this->clear();
            $this->userId = $userAuthentication->UserId;
        }
        $this->userAuthentication = $userAuthentication;
        return $this;
    }

    // --- Admin ---

    /**
     * @return bool
     */
    public function hasAdmin(): bool
    {
        return ($this->admin !== null);
    }

    /**
     * Return Admin object
     * @param bool $isReadOnlyDb
     * @return Admin|null
     */
    public function getAdmin(bool $isReadOnlyDb = false): ?Admin
    {
        if (
            $this->admin === null
            && $this->userId > 0
        ) {
            $this->admin = $this->getUserLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadAdmin($this->userId, $isReadOnlyDb);
        }
        return $this->admin;
    }

    /**
     * Return Admin object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return Admin
     */
    public function getAdminOrCreate(bool $isReadOnlyDb = false): Admin
    {
        $this->admin = $this->getAdmin($isReadOnlyDb);
        if ($this->admin === null) {
            $this->admin = $this->createEntityFactory()->admin();
            $this->admin->UserId = $this->userId;
        }
        return $this->admin;
    }

    /**
     * Return Admin object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return Admin
     */
    public function getAdminOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): Admin
    {
        $this->admin = $this->getAdminOrCreate($isReadOnlyDb);
        if (!$this->admin->Id) {
            $this->getAdminWriteRepository()->saveWithModifier($this->admin, $editorUserId);
        }
        return $this->admin;
    }

    /**
     * Set Admin object
     * @param Admin|null $admin
     * @return static
     */
    public function setAdmin(?Admin $admin = null): static
    {
        if (
            $admin
            && $admin->UserId !== $this->userId
        ) {
            $this->clear();
            $this->userId = $admin->UserId;
        }
        $this->admin = $admin;
        return $this;
    }

    // --- Bidder ---

    /**
     * @return bool
     */
    public function hasBidder(): bool
    {
        return ($this->bidder !== null);
    }

    /**
     * Return Bidder object
     * @param bool $isReadOnlyDb
     * @return Bidder|null
     */
    public function getBidder(bool $isReadOnlyDb = false): ?Bidder
    {
        if (
            $this->bidder === null
            && $this->userId > 0
        ) {
            $this->bidder = $this->getUserLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadBidder($this->userId, $isReadOnlyDb);
        }
        return $this->bidder;
    }

    /**
     * Return Bidder object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return Bidder
     */
    public function getBidderOrCreate(bool $isReadOnlyDb = false): Bidder
    {
        $this->bidder = $this->getBidder($isReadOnlyDb);
        if ($this->bidder === null) {
            $this->bidder = $this->createEntityFactory()->bidder();
            $this->bidder->UserId = $this->userId;
        }
        return $this->bidder;
    }

    /**
     * Return Bidder object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return Bidder
     */
    public function getBidderOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): Bidder
    {
        $this->bidder = $this->getBidderOrCreate($isReadOnlyDb);
        if (!$this->bidder->Id) {
            $this->getBidderWriteRepository()->saveWithModifier($this->bidder, $editorUserId);
        }
        return $this->bidder;
    }

    /**
     * Set Bidder object
     * @param Bidder|null $bidder
     * @return static
     */
    public function setBidder(?Bidder $bidder = null): static
    {
        if (
            $bidder
            && $bidder->UserId !== $this->userId
        ) {
            $this->clear();
            $this->userId = $bidder->UserId;
        }
        $this->bidder = $bidder;
        return $this;
    }

    // --- Consignor ---

    /**
     * @return bool
     */
    public function hasConsignor(): bool
    {
        return ($this->consignor !== null);
    }

    /**
     * Return Consignor object
     * @param bool $isReadOnlyDb
     * @return Consignor|null
     */
    public function getConsignor(bool $isReadOnlyDb = false): ?Consignor
    {
        if (
            $this->consignor === null
            && $this->userId > 0
        ) {
            $this->consignor = $this->getUserLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadConsignor($this->userId, $isReadOnlyDb);
        }
        return $this->consignor;
    }

    /**
     * Return Consignor object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return Consignor
     */
    public function getConsignorOrCreate(bool $isReadOnlyDb = false): Consignor
    {
        $this->consignor = $this->getConsignor($isReadOnlyDb);
        if ($this->consignor === null) {
            $this->consignor = $this->createEntityFactory()->consignor();
            $this->consignor->UserId = $this->userId;
        }
        return $this->consignor;
    }

    /**
     * Return Consignor object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return Consignor
     */
    public function getConsignorOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): Consignor
    {
        $this->consignor = $this->getConsignorOrCreate($isReadOnlyDb);
        if (!$this->consignor->Id) {
            $this->getConsignorWriteRepository()->saveWithModifier($this->consignor, $editorUserId);
        }
        return $this->consignor;
    }

    /**
     * Set Consignor object
     * @param Consignor|null $consignor
     * @return static
     */
    public function setConsignor(?Consignor $consignor = null): static
    {
        if (
            $consignor
            && $consignor->UserId !== $this->userId
        ) {
            $this->clear();
            $this->userId = $consignor->UserId;
        }
        $this->consignor = $consignor;
        return $this;
    }

    // --- Privilege checking methods ---

    /**
     * @return bool
     */
    public function hasAdminRole(): bool
    {
        if ($this->hasAdminRole === null) {
            $this->hasAdminRole = RoleChecker::new()->isAdmin($this->userId);
        }
        return $this->hasAdminRole;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableAdminRole(bool $enable): static
    {
        $this->hasAdminRole = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasBidderRole(): bool
    {
        if ($this->hasBidderRole === null) {
            $this->hasBidderRole = RoleChecker::new()->isBidder($this->userId);
        }
        return $this->hasBidderRole;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableBidderRole(bool $enable): static
    {
        $this->hasBidderRole = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasConsignorRole(): bool
    {
        if ($this->hasConsignorRole === null) {
            $this->hasConsignorRole = RoleChecker::new()->isConsignor($this->userId);
        }
        return $this->hasConsignorRole;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableConsignorRole(bool $enable): static
    {
        $this->hasConsignorRole = $enable;
        return $this;
    }

    // --- get Privilege Checkers initialized by current user ---

    /**
     * @return AdminPrivilegeChecker
     */
    public function getAdminPrivilegeChecker(): AdminPrivilegeChecker
    {
        if ($this->adminPrivilegeChecker === null) {
            $this->adminPrivilegeChecker = AdminPrivilegeChecker::new()
                ->initByAdmin($this->getAdmin());
        }
        return $this->adminPrivilegeChecker;
    }

    /**
     * @param AdminPrivilegeChecker $adminPrivilegeChecker
     * @return static
     */
    public function setAdminPrivilegeChecker(AdminPrivilegeChecker $adminPrivilegeChecker): static
    {
        $this->adminPrivilegeChecker = $adminPrivilegeChecker;
        return $this;
    }

    /**
     * @return BidderPrivilegeChecker
     */
    public function getBidderPrivilegeChecker(): BidderPrivilegeChecker
    {
        if ($this->bidderPrivilegeChecker === null) {
            $this->bidderPrivilegeChecker = BidderPrivilegeChecker::new()
                ->initByBidder($this->getBidder());
        }
        return $this->bidderPrivilegeChecker;
    }

    /**
     * @param BidderPrivilegeChecker $bidderPrivilegeChecker
     * @return static
     */
    public function setBidderPrivilegeChecker(BidderPrivilegeChecker $bidderPrivilegeChecker): static
    {
        $this->bidderPrivilegeChecker = $bidderPrivilegeChecker;
        return $this;
    }

    /**
     * @return ConsignorPrivilegeChecker
     */
    public function getConsignorPrivilegeChecker(): ConsignorPrivilegeChecker
    {
        if ($this->consignorPrivilegeChecker === null) {
            $this->consignorPrivilegeChecker = ConsignorPrivilegeChecker::new()
                ->initByConsignor($this->getConsignor());
        }
        return $this->consignorPrivilegeChecker;
    }

    /**
     * @param ConsignorPrivilegeChecker $consignorPrivilegeChecker
     * @return static
     */
    public function setConsignorPrivilegeChecker(ConsignorPrivilegeChecker $consignorPrivilegeChecker): static
    {
        $this->consignorPrivilegeChecker = $consignorPrivilegeChecker;
        return $this;
    }
}
