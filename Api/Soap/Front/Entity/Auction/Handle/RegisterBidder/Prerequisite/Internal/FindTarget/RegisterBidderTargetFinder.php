<?php
/**
 * SAM-5041: Soap API RegisterBidder improvement
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\Prerequisite\Internal\FindTarget;

use Sam\Api\Soap\Front\Entity\Auction\Handle\Common\AuctionSoapConstants;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class RegisterBidderObjectsResolver
 * @package Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\ResolveObject
 */
class RegisterBidderTargetFinder extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get auction by auction id, auction event id or auction sync key
     * @param string $auctionKey
     * @param string $namespace
     * @param int|null $namespaceId
     * @param int $systemAccountId
     * @param bool $isReadOnlyDb
     * @return int|null null, when cannot find
     */
    public function findAuctionId(
        string $auctionKey,
        string $namespace,
        ?int $namespaceId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): ?int {
        if (in_array($namespace, [AuctionSoapConstants::NAMESPACE_USER_ID, AuctionSoapConstants::NAMESPACE_USER_NAME], true)) {
            $auction = $this->getAuctionLoader()->load((int)$auctionKey, $isReadOnlyDb);
        } else {
            $auction = $this->getAuctionLoader()->loadBySyncKey($auctionKey, $namespaceId, $systemAccountId, $isReadOnlyDb);
        }
        return $auction->Id ?? null;
    }

    /**
     * Get user by id, username or sync key
     * @param string $userKey
     * @param string $namespace
     * @param int|null $namespaceId
     * @param int $systemAccountId
     * @param bool $isReadOnlyDb
     * @return int|null null, when cannot find
     */
    public function findUserId(
        string $userKey,
        string $namespace,
        ?int $namespaceId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): ?int {
        if ($namespace === AuctionSoapConstants::NAMESPACE_USER_ID) {
            $user = $this->getUserLoader()->load((int)$userKey, $isReadOnlyDb);
        } elseif ($namespace === AuctionSoapConstants::NAMESPACE_USER_NAME) {
            $user = $this->getUserLoader()->loadByUsername($userKey, $isReadOnlyDb);
        } else {
            $user = $this->getUserLoader()->loadBySyncKey($userKey, $namespaceId, $systemAccountId, $isReadOnlyDb);
        }
        return $user->Id ?? null;
    }
}
