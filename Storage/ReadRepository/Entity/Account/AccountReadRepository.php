<?php
/**
 * General repository for Account entity
 *
 * SAM-3634: Account repository class integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           17 Feb, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of accounts filtered by criteria
 * $accountRepository = Sam\Storage\ReadRepository\Entity\Account\AccountReadRepository::new()
 *     ->enableReadOnlyDb(true)
 *     ->filterId(cfg()->core->portal->mainAccountId);           \\ search for main account
 * $isFound = $accountRepository->exist();
 * $count = $accountRepository->count();
 * $accounts = $accountRepository->loadEntities();
 *
 * // Sample2. Load single account
 * $accountRepository = Sam\Storage\ReadRepository\Entity\Account\AccountReadRepository::new()
 *     ->filterId(1);
 * $account = $accountRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\Account;

use Sam\Core\Constants;

/**
 * Class AccountReadRepository
 * @package Sam\Storage\ReadRepository\Entity\Account
 */
class AccountReadRepository extends AbstractAccountReadRepository
{
    protected array $joins = [
        'auction' => 'JOIN auction a ON a.account_id = acc.id',
        'entity_sync' => 'JOIN entity_sync esync ON (acc.id = esync.entity_id AND esync.entity_type = ' . Constants\EntitySync::TYPE_ACCOUNT . ')',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join auction table
     * @return static
     */
    public function joinAuction(): static
    {
        $this->join('auction');
        return $this;
    }

    /**
     * Join auction table and filter by a.auction_status_id
     * @param int|int[] $auctionStatusIds
     * @return static
     */
    public function joinAuctionFilterAuctionStatusId(int|array|null $auctionStatusIds): static
    {
        $this->joinAuction();
        $this->filterArray('a.auction_status_id', $auctionStatusIds);
        return $this;
    }

    /**
     * Join auction table and filter by a.id
     * @param int|int[] $auctionId
     * @return static
     */
    public function joinAuctionFilterId(int|array|null $auctionId): static
    {
        $this->joinAuction();
        $this->filterArray('a.id', $auctionId);
        return $this;
    }

    /**
     * Join `entity_sync` table
     * @return static
     */
    public function joinAccountSync(): static
    {
        $this->join('entity_sync');
        return $this;
    }

    /**
     * Define filtering by esync.sync_namespace_id
     * @param int|int[] $syncNamespaceId
     * @return static
     */
    public function joinAccountSyncFilterSyncNamespaceId(int|array|null $syncNamespaceId): static
    {
        $this->joinAccountSync();
        $this->filterArray('esync.sync_namespace_id', $syncNamespaceId);
        return $this;
    }

    /**
     * Define filtering by esync.key
     * @param string|string[] $key
     * @return static
     */
    public function joinAccountSyncFilterKey(string|array|null $key): static
    {
        $this->joinAccountSync();
        $this->filterArray('esync.`key`', $key);
        return $this;
    }
}
