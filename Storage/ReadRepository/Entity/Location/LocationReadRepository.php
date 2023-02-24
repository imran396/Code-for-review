<?php
/**
 * General repository for Location entity
 *
 * SAM-3638: Location repository and manager
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Pavel Mitkovskiy <pmitkovskiy@samauctionsoftware.com>
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           08 Mar, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\Location;

use Sam\Core\Constants;

/**
 * Class LocationReadRepository
 * @package Sam\Storage\ReadRepository\Entity\Location
 */
class LocationReadRepository extends AbstractLocationReadRepository
{
    protected array $joins = [
        'auction' => 'JOIN auction AS a ON a.invoice_location_id = loc.id',
        'account' => 'JOIN account AS acc ON acc.id = loc.account_id',
        'entity_sync' => 'JOIN entity_sync esync ON (loc.id = esync.entity_id AND esync.entity_type = ' . Constants\EntitySync::TYPE_LOCATION . ')',
    ];

    /**
     * Class instantiation method
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
     * Define filtering by a.id
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
     * Join sccount table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
    }

    /**
     * Define ordering by acc.name
     * @param bool $ascending
     * @return static
     */
    public function joinAccountOrderByName(bool $ascending = true): static
    {
        $this->joinAccount();
        $this->order('acc.name', $ascending);
        return $this;
    }

    /**
     * Join `entity_sync` table
     * @return static
     */
    public function joinLocationSync(): static
    {
        $this->join('entity_sync');
        return $this;
    }

    /**
     * Define filtering by esync.sync_namespace_id
     * @param int|int[] $syncNamespaceId
     * @return static
     */
    public function joinLocationSyncFilterSyncNamespaceId(int|array|null $syncNamespaceId): static
    {
        $this->joinLocationSync();
        $this->filterArray('esync.sync_namespace_id', $syncNamespaceId);
        return $this;
    }

    /**
     * Define filtering by esync.key
     * @param string|string[] $key
     * @return static
     */
    public function joinLocationSyncFilterKey(string|array|null $key): static
    {
        $this->joinLocationSync();
        $this->filterArray('esync.`key`', $key);
        return $this;
    }
}
