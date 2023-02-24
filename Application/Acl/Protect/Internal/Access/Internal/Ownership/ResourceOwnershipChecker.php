<?php
/**
 * SAM-9538: Decouple ACL checking logic from front controller
 *
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Application\Acl\Protect\Internal\Access\Internal\Ownership;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;

/**
 * Class ResourceOwnershipChecker
 * @package Sam\Application\Acl
 */
class ResourceOwnershipChecker extends CustomizableClass
{
    use AuctionReadRepositoryCreateTrait;
    use UserReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $editorUserId
     * @param int $resourceEntityId
     * @param string $resourceController
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isOwner(
        ?int $editorUserId,
        int $resourceEntityId,
        string $resourceController,
        bool $isReadOnlyDb = false
    ): bool {
        $hasPermission = false;
        if ($resourceController === Constants\AdminRoute::C_MANAGE_AUCTIONS) {
            if (
                $editorUserId
                && $this->isOwnerOfAuction($editorUserId, $resourceEntityId, $isReadOnlyDb)
            ) {
                $hasPermission = true;
            }
        } elseif ($resourceController === Constants\AdminRoute::C_MANAGE_USERS) {
            if (
                $editorUserId
                && $this->isOwnerOfUser($editorUserId, $resourceEntityId, $isReadOnlyDb)
            ) {
                $hasPermission = true;
            }
        }
        return $hasPermission;
    }

    /**
     * Check if user is owner of auction
     * @param int $checkingUserId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    protected function isOwnerOfAuction(int $checkingUserId, int $auctionId, bool $isReadOnlyDb = false): bool
    {
        return $this->createAuctionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterCreatedBy($checkingUserId)
            ->filterId($auctionId)
            ->exist();
    }

    /**
     * Check user's owner
     * @param int $checkingUserId
     * @param int $targetUserId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    protected function isOwnerOfUser(int $checkingUserId, int $targetUserId, bool $isReadOnlyDb = false): bool
    {
        return $this->createUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterCreatedBy($checkingUserId)
            ->filterId($targetUserId)
            ->exist();
    }

}
