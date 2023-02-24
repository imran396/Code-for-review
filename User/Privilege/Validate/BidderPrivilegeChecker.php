<?php
/**
 * Bidder privileges checking service.
 *
 * Init service before privilege check using one of these methods:
 * initByBidder(\Bidder) - set bidder info record
 * initByUser(\User) - bidder info will be loaded by user id
 * initByUserId(int) - '' - '' -
 *
 * Related tickets:
 * SAM-3560: Privilege checker class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           31 Jan, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Privilege\Validate;

use Bidder;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * Class BidderPrivilegeChecker
 * @package Sam\User\Privilege\Validate
 */
class BidderPrivilegeChecker extends CustomizableClass
{
    use UserLoaderAwareTrait;

    protected ?Bidder $bidder = null;
    /**
     * Get data from read-only db, if read-only db is available
     */
    protected bool $isReadOnlyDb = false;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Init service with \Bidder record, that stores privileges
     * @param Bidder|null $bidder null - for case, when user isn't bidder
     * @return static
     */
    public function initByBidder(?Bidder $bidder): static
    {
        $this->bidder = $bidder;
        return $this;
    }

    /**
     * Alternative way to init checker. Bidder info will be loaded by passed user id.
     * @param int|null $userId null - for anonymous user
     * @return static
     */
    public function initByUserId(?int $userId = null): static
    {
        $bidder = $userId ? $this->getUserLoader()->loadBidder($userId, $this->isReadOnlyDb) : null;
        $this->initByBidder($bidder);
        return $this;
    }

    /**
     * Alternative way to init checker. Bidder info will be loaded by passed user.
     * @param User|null $user null - for anonymous user
     * @return static
     */
    public function initByUser(?User $user): static
    {
        $userId = $user->Id ?? null;
        $this->initByUserId($userId);
        return $this;
    }

    /**
     * Use read-only db, if it is available
     * @param bool $enable
     * @return static
     */
    public function enableReadOnlyDb(bool $enable): static
    {
        $this->isReadOnlyDb = $enable;
        return $this;
    }

    /**
     * Has bidder role
     * @return bool
     */
    public function isBidder(): bool
    {
        $has = $this->getBidder() instanceof Bidder
            && $this->getBidder()->Id > 0;
        return $has;
    }

    /**
     * @return bool
     */
    public function hasPrivilegeForAgent(): bool
    {
        $bidder = $this->getBidder();
        return $bidder && $bidder->isAgent();
    }

    /**
     * @return bool
     */
    public function hasPrivilegeForPreferred(): bool
    {
        $bidder = $this->getBidder();
        return $bidder && $bidder->isPreferred();
    }

    /**
     * @return bool
     */
    public function hasPrivilegeForHouse(): bool
    {
        $bidder = $this->getBidder();
        return $bidder && $bidder->isHouse();
    }

    /**
     * @return Bidder|null
     */
    protected function getBidder(): ?Bidder
    {
        return $this->bidder;
    }

    /**
     * Check if Agent handles Buyer
     * @param int $buyerId
     * @return bool
     */
    public function isAgentOfBuyer(int $buyerId): bool
    {
        $is = false;
        if (
            $this->isBidder()
            && $this->hasPrivilegeForAgent()
        ) {
            $buyerUser = $this->getUserLoader()->load($buyerId, $this->isReadOnlyDb);
            if (!$buyerUser) {
                log_error(
                    "Available buyer user not found, when checking agent of buyer"
                    . composeSuffix(['u' => $buyerId])
                );
                return false;
            }
            $is = $buyerUser->isCreator($this->getBidder()->UserId);
        }
        return $is;
    }
}
