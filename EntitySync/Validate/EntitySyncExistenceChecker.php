<?php
/**
 * Help methods for different entity sync validations for existence
 *
 * SAM-5015: Unite sync tables data scheme
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 26, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntitySync\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\EntitySync\EntitySyncReadRepositoryCreateTrait;

/**
 * Class EntitySyncExistenceChecker
 * @package Sam\EntitySync\Validate
 */
class EntitySyncExistenceChecker extends CustomizableClass
{
    use EntitySyncReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $key
     * @param int $syncNamespaceId
     * @param array $skipAccountIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existForAccountByKeyAndSyncNamespaceId(
        string $key,
        int $syncNamespaceId,
        array $skipAccountIds = [],
        bool $isReadOnlyDb = false
    ): bool {
        return $this->existByTypeAndKeyAndSyncNamespaceId(
            Constants\EntitySync::TYPE_ACCOUNT,
            $key,
            $syncNamespaceId,
            $skipAccountIds,
            $isReadOnlyDb
        );
    }

    /**
     * @param string $key
     * @param int $syncNamespaceId
     * @param array $skipAuctionIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existForAuctionByKeyAndSyncNamespaceId(
        string $key,
        int $syncNamespaceId,
        array $skipAuctionIds = [],
        bool $isReadOnlyDb = false
    ): bool {
        return $this->existByTypeAndKeyAndSyncNamespaceId(
            Constants\EntitySync::TYPE_AUCTION,
            $key,
            $syncNamespaceId,
            $skipAuctionIds,
            $isReadOnlyDb
        );
    }

    /**
     * @param string $key
     * @param int $syncNamespaceId
     * @param array $skipAuctionLotIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existForAuctionLotByKeyAndSyncNamespaceId(
        string $key,
        int $syncNamespaceId,
        array $skipAuctionLotIds = [],
        bool $isReadOnlyDb = false
    ): bool {
        return $this->existByTypeAndKeyAndSyncNamespaceId(
            Constants\EntitySync::TYPE_AUCTION_LOT_ITEM,
            $key,
            $syncNamespaceId,
            $skipAuctionLotIds,
            $isReadOnlyDb
        );
    }

    /**
     * @param string $key
     * @param int $syncNamespaceId
     * @param array $skipLotItemIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existForLotItemByKeyAndSyncNamespaceId(
        string $key,
        int $syncNamespaceId,
        array $skipLotItemIds = [],
        bool $isReadOnlyDb = false
    ): bool {
        return $this->existByTypeAndKeyAndSyncNamespaceId(
            Constants\EntitySync::TYPE_LOT_ITEM,
            $key,
            $syncNamespaceId,
            $skipLotItemIds,
            $isReadOnlyDb
        );
    }

    /**
     * @param string $key
     * @param int $syncNamespaceId
     * @param array $skipUserIds
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existForUserByKeyAndSyncNamespaceId(
        string $key,
        int $syncNamespaceId,
        array $skipUserIds = [],
        int $accountId = null,
        bool $isReadOnlyDb = false
    ): bool {
        return $this->existByTypeAndKeyAndSyncNamespaceId(
            Constants\EntitySync::TYPE_USER,
            $key,
            $syncNamespaceId,
            $skipUserIds,
            $isReadOnlyDb,
            $accountId
        );
    }

    /**
     * Check, if entity_sync.key, entity_sync.sync_namespace_id exist
     * @param int $type
     * @param string $key
     * @param int $syncNamespaceId
     * @param array $skipEntityIds
     * @param bool $isReadOnlyDb
     * @param int|null $accountId
     * @return bool
     */
    public function existByTypeAndKeyAndSyncNamespaceId(
        int $type,
        string $key,
        int $syncNamespaceId,
        array $skipEntityIds = [],
        bool $isReadOnlyDb = false,
        int $accountId = null
    ): bool {
        $repo = $this->createEntitySyncReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterEntityType($type)
            ->filterKey($key)
            ->filterSyncNamespaceId($syncNamespaceId)
            ->joinEntityFilterActive($type)
            ->skipEntityId($skipEntityIds);
        if ($accountId) {
            $repo->joinEntityFilterAccountId($type, $accountId);
        }
        return $repo->exist();
    }
}
