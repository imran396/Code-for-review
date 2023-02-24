<?php
/**
 * General repository for SyncNamespace entity
 *
 * SAM-3689 : Repositories for sync tables  https://bidpath.atlassian.net/browse/SAM-3689
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           11 May, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of SyncNamespace filtered by criteria
 * $syncNamespaceRepository = \Sam\Storage\ReadRepository\Entity\SyncNamespace\SyncNamespaceReadRepository::new()
 *     ->filterActive($active)          // single value passed as argument
 *     ->filterId($ids);   // array passed as argument
 *
 * $isFound = $syncNamespaceRepository->exist();
 * $count = $syncNamespaceRepository->count();
 * $syncNamespace = $syncNamespaceRepository->loadEntities();
 *
 * // Sample2. Load single SyncNamespace
 * $syncNamespaceRepository = \Sam\Storage\ReadRepository\Entity\SyncNamespace\SyncNamespaceReadRepository::new()
 *     ->filterId(1);
 * $syncNamespace = $syncNamespaceRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\SyncNamespace;

use Sam\Core\Constants;

/**
 * Class SyncNamespaceReadRepository
 * @package Sam\Storage\ReadRepository\Entity\SyncNamespace
 */
class SyncNamespaceReadRepository extends AbstractSyncNamespaceReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'entity_sync' => 'JOIN entity_sync esync ON (esync.sync_namespace_id = sn.id AND esync.entity_type = ' . Constants\EntitySync::TYPE_LOT_ITEM . ')',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function joinLotItemSync(): static
    {
        $this->join('entity_sync');
        return $this;
    }

    /**
     * @param int|int[] $lotItemId
     * @return $this
     */
    public function joinLotItemSyncFilterLotItemId(int|array|null $lotItemId): static
    {
        $this->joinLotItemSync();
        $this->filterArray('esync.entity_id', $lotItemId);
        return $this;
    }
}
