<?php
/**
 * Help methods for entity sync loading
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

namespace Sam\EntitySync\Load;

use EntitySync;
use Sam\Core\Constants;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\EntitySync\EntitySyncReadRepositoryCreateTrait;

/**
 * Class EntitySyncLoader
 * @package Sam\EntitySync\Load
 */
class EntitySyncLoader extends EntityLoaderBase
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
     * Load account sync by account.id
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return EntitySync|null
     */
    public function loadForAccountByEntityId(int $accountId, bool $isReadOnlyDb = false): ?EntitySync
    {
        return $this->loadByTypeAndEntityId(
            Constants\EntitySync::TYPE_ACCOUNT,
            $accountId,
            $isReadOnlyDb
        );
    }

    /**
     * Load auction sync by account.id
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return EntitySync|null
     */
    public function loadForAuctionByEntityId(int $auctionId, bool $isReadOnlyDb = false): ?EntitySync
    {
        return $this->loadByTypeAndEntityId(
            Constants\EntitySync::TYPE_AUCTION,
            $auctionId,
            $isReadOnlyDb
        );
    }

    /**
     * Load auction lot sync by account.id
     * @param int $auctionLotId
     * @param bool $isReadOnlyDb
     * @return EntitySync|null
     */
    public function loadForAuctionLotByEntityId(int $auctionLotId, bool $isReadOnlyDb = false): ?EntitySync
    {
        return $this->loadByTypeAndEntityId(
            Constants\EntitySync::TYPE_AUCTION_LOT_ITEM,
            $auctionLotId,
            $isReadOnlyDb
        );
    }

    /**
     * @param string $key
     * @param int $syncNamespaceId
     * @param int|null $accountId null results with null entity
     * @param bool $isReadOnlyDb
     * @return EntitySync|null
     */
    public function loadForAccount(
        string $key,
        int $syncNamespaceId,
        ?int $accountId = null,
        bool $isReadOnlyDb = false
    ): ?EntitySync {
        return $this->loadByType(
            Constants\EntitySync::TYPE_ACCOUNT,
            $key,
            $syncNamespaceId,
            $accountId,
            $isReadOnlyDb
        );
    }

    /**
     * @param string $key
     * @param int $syncNamespaceId
     * @param int|null $accountId null results with null entity
     * @param bool $isReadOnlyDb
     * @return EntitySync|null
     */
    public function loadForAuction(
        string $key,
        int $syncNamespaceId,
        ?int $accountId = null,
        bool $isReadOnlyDb = false
    ): ?EntitySync {
        return $this->loadByType(
            Constants\EntitySync::TYPE_AUCTION,
            $key,
            $syncNamespaceId,
            $accountId,
            $isReadOnlyDb
        );
    }

    /**
     * @param string $key
     * @param int $syncNamespaceId
     * @param int|null $accountId null results with null entity
     * @param bool $isReadOnlyDb
     * @return EntitySync|null
     */
    public function loadForAuctionLot(
        string $key,
        int $syncNamespaceId,
        ?int $accountId = null,
        bool $isReadOnlyDb = false
    ): ?EntitySync {
        return $this->loadByType(
            Constants\EntitySync::TYPE_AUCTION_LOT_ITEM,
            $key,
            $syncNamespaceId,
            $accountId,
            $isReadOnlyDb
        );
    }

    /**
     * @param string $key
     * @param int $syncNamespaceId
     * @param int|null $accountId null results with null entity
     * @param bool $isReadOnlyDb
     * @return EntitySync|null
     */
    public function loadForLotItem(
        string $key,
        int $syncNamespaceId,
        ?int $accountId = null,
        bool $isReadOnlyDb = false
    ): ?EntitySync {
        return $this->loadByType(
            Constants\EntitySync::TYPE_LOT_ITEM,
            $key,
            $syncNamespaceId,
            $accountId,
            $isReadOnlyDb
        );
    }

    /**
     * @param string $key
     * @param int $syncNamespaceId
     * @param int|null $accountId null results with null entity
     * @param bool $isReadOnlyDb
     * @return EntitySync|null
     */
    public function loadForUser(
        string $key,
        int $syncNamespaceId,
        ?int $accountId = null,
        bool $isReadOnlyDb = false
    ): ?EntitySync {
        return $this->loadByType(
            Constants\EntitySync::TYPE_USER,
            $key,
            $syncNamespaceId,
            $accountId,
            $isReadOnlyDb
        );
    }

    /**
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return EntitySync|null
     */
    public function loadForLotItemByEntityId(int $lotItemId, bool $isReadOnlyDb = false): ?EntitySync
    {
        return $this->loadByTypeAndEntityId(
            Constants\EntitySync::TYPE_LOT_ITEM,
            $lotItemId,
            $isReadOnlyDb
        );
    }

    /**
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return EntitySync|null
     */
    public function loadForUserByEntityId(int $userId, bool $isReadOnlyDb = false): ?EntitySync
    {
        return $this->loadByTypeAndEntityId(
            Constants\EntitySync::TYPE_USER,
            $userId,
            $isReadOnlyDb
        );
    }

    /**
     * Load entity sync by entity.id
     * @param int $type
     * @param int $entityId
     * @param bool $isReadOnlyDb
     * @return EntitySync|null
     */
    public function loadByTypeAndEntityId(int $type, int $entityId, bool $isReadOnlyDb = false): ?EntitySync
    {
        if (!$entityId) {
            return null;
        }

        return $this->createEntitySyncReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterEntityType($type)
            ->filterEntityId($entityId)
            ->joinEntityFilterActive($type)
            ->loadEntity();
    }

    /**
     * Load entity sync by entity_sync.key, entity_sync.sync_namespace_id
     * @param int $type
     * @param string $key
     * @param int $syncNamespaceId
     * @param int|null $accountId null results with null entity
     * @param bool $isReadOnlyDb
     * @return EntitySync|null
     */
    public function loadByType(
        int $type,
        string $key,
        int $syncNamespaceId,
        ?int $accountId = null,
        bool $isReadOnlyDb = false
    ): ?EntitySync {
        $entitySync = $this->createEntitySyncReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterEntityType($type)
            ->filterKey($key)
            ->filterSyncNamespaceId($syncNamespaceId)
            ->joinEntityFilterAccountId($type, $accountId)
            ->joinEntityFilterActive($type)
            ->loadEntity();
        return $entitySync;
    }
}
