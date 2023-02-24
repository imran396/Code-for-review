<?php
/**
 * Trait intends to describe user, who run an action.
 * In general case, it it currently authenticated user, or null for anonymous visitor.
 *
 * SAM-5640: Apply EditorUserAwareTrait
 * SAM-4729: General logic for editor services
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01/08/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Save\AwareTrait;

use Admin;
use Bidder;
use Consignor;
use Sam\Storage\Entity\Aggregate\UserAggregate;
use Sam\User\Auth\Identity\AuthIdentityManager;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;
use Sam\User\Privilege\Validate\BidderPrivilegeChecker;
use Sam\User\Privilege\Validate\ConsignorPrivilegeChecker;
use User;
use UserAuthentication;
use UserBilling;
use UserInfo;
use UserShipping;

/**
 * Trait EditorUserAwareTrait
 * @package Sam\Core\Save\AwareTrait
 */
trait EditorUserAwareTrait
{
    protected ?UserAggregate $editorUserAggregate = null;

    /**
     * @return UserAggregate
     */
    public function getEditorUserAggregate(): UserAggregate
    {
        if ($this->editorUserAggregate === null) {
            $this->editorUserAggregate = UserAggregate::new();
            $this->editorUserAggregate->setUserId($this->getEditorUserId());
        }
        return $this->editorUserAggregate;
    }

    /**
     * @param UserAggregate $userAggregate
     * @return static
     */
    public function setEditorUserAggregate(UserAggregate $userAggregate): static
    {
        $this->editorUserAggregate = $userAggregate;
        return $this;
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function equalEditorUserId(?int $editorUserId): bool
    {
        return $this->getEditorUserId() === $editorUserId;
    }

    /**
     * @return int|null
     */
    public function getEditorUserId(): ?int
    {
        $userId = $this->getEditorUserAggregate()->getUserId();
        if ($userId === null) {
            $userId = AuthIdentityManager::new()->getUserId();
            $this->setEditorUserId($userId);
        }
        return $userId;
    }

    /**
     * @param int|null $userId
     * @param bool $fresh
     * @return static
     */
    public function setEditorUserId(?int $userId, bool $fresh = false): static
    {
        if ($fresh) {
            $this->getEditorUserAggregate()->clear();
        }
        $this->getEditorUserAggregate()->setUserId($userId);
        return $this;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return User|null
     */
    public function getEditorUser(bool $isReadOnlyDb = false): ?User
    {
        $user = $this->getEditorUserAggregate()->getUser($isReadOnlyDb);
        return $user;
    }

    /**
     * @param User|null $user
     * @return static
     */
    public function setEditorUser(?User $user = null): static
    {
        $this->getEditorUserAggregate()->setUser($user);
        return $this;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return UserInfo|null
     */
    public function getEditorUserInfo(bool $isReadOnlyDb = false): ?UserInfo
    {
        $userInfo = $this->getEditorUserAggregate()->getUserInfo($isReadOnlyDb);
        return $userInfo;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return UserInfo
     */
    public function getEditorUserInfoOrCreate(bool $isReadOnlyDb = false): UserInfo
    {
        $userInfo = $this->getEditorUserAggregate()->getUserInfoOrCreate($isReadOnlyDb);
        return $userInfo;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return UserBilling
     */
    public function getEditorUserBillingOrCreate(bool $isReadOnlyDb = false): UserBilling
    {
        $userBilling = $this->getEditorUserAggregate()->getUserBillingOrCreate($isReadOnlyDb);
        return $userBilling;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return UserShipping
     */
    public function getEditorUserShippingOrCreate(bool $isReadOnlyDb = false): UserShipping
    {
        $userShipping = $this->getEditorUserAggregate()->getUserShippingOrCreate($isReadOnlyDb);
        return $userShipping;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return UserAuthentication
     */
    public function getEditorUserAuthenticationOrCreate(bool $isReadOnlyDb = false): UserAuthentication
    {
        $userAuthentication = $this->getEditorUserAggregate()->getUserAuthenticationOrCreate($isReadOnlyDb);
        return $userAuthentication;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return Admin
     */
    public function getEditorAdminOrCreate(bool $isReadOnlyDb = false): Admin
    {
        $admin = $this->getEditorUserAggregate()->getAdminOrCreate($isReadOnlyDb);
        return $admin;
    }

    /**
     * @param Admin|null $admin
     * @return static
     */
    public function setEditorAdmin(?Admin $admin = null): static
    {
        $this->getEditorUserAggregate()->setAdmin($admin);
        return $this;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return Bidder|null
     */
    public function getEditorBidder(bool $isReadOnlyDb = false): ?Bidder
    {
        $bidder = $this->getEditorUserAggregate()->getBidder($isReadOnlyDb);
        return $bidder;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return Bidder
     */
    public function getEditorBidderOrCreate(bool $isReadOnlyDb = false): Bidder
    {
        $bidder = $this->getEditorUserAggregate()->getBidderOrCreate($isReadOnlyDb);
        return $bidder;
    }

    /**
     * @param Bidder|null $bidder
     * @return static
     * @noinspection PhpUnused
     */
    public function setEditorBidder(?Bidder $bidder = null): static
    {
        $this->getEditorUserAggregate()->setBidder($bidder);
        return $this;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return Consignor
     */
    public function getEditorConsignorOrCreate(bool $isReadOnlyDb = false): Consignor
    {
        $consignor = $this->getEditorUserAggregate()->getConsignorOrCreate($isReadOnlyDb);
        return $consignor;
    }

    /**
     * @param Consignor|null $consignor
     * @return static
     * @noinspection PhpUnused
     */
    public function setEditorConsignor(?Consignor $consignor = null): static
    {
        $this->getEditorUserAggregate()->setConsignor($consignor);
        return $this;
    }

    /**
     * @return bool
     */
    public function hasEditorUserAdminRole(): bool
    {
        $has = $this->getEditorUserAggregate()->hasAdminRole();
        return $has;
    }

    /**
     * @return bool
     */
    public function hasEditorUserBidderRole(): bool
    {
        $has = $this->getEditorUserAggregate()->hasBidderRole();
        return $has;
    }

    /**
     * @return bool
     */
    public function hasEditorUserConsignorRole(): bool
    {
        $has = $this->getEditorUserAggregate()->hasConsignorRole();
        return $has;
    }

    /**
     * @return AdminPrivilegeChecker
     */
    public function getEditorUserAdminPrivilegeChecker(): AdminPrivilegeChecker
    {
        return $this->getEditorUserAggregate()->getAdminPrivilegeChecker();
    }

    /**
     * @return BidderPrivilegeChecker
     */
    public function getEditorUserBidderPrivilegeChecker(): BidderPrivilegeChecker
    {
        return $this->getEditorUserAggregate()->getBidderPrivilegeChecker();
    }

    /**
     * @return ConsignorPrivilegeChecker
     */
    public function getEditorUserConsignorPrivilegeChecker(): ConsignorPrivilegeChecker
    {
        return $this->getEditorUserAggregate()->getConsignorPrivilegeChecker();
    }
}
