<?php
/**
 * Search index queue functionality
 *
 * SAM-6474: Move full-text search query building and queue management logic to \Sam\SearchIndex namespace
 * SAM-1020: Front End - Search Page - Keyword Search Improvements
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Mar 01, 2012
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SearchIndex;

use Sam;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\DeleteRepository\Entity\IndexQueue\IndexQueueDeleteRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\IndexQueue\IndexQueueReadRepositoryCreateTrait;

/**
 * Class SearchIndexQueue
 * @package Sam\SearchIndex
 */
class SearchIndexQueue extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DbConnectionTrait;
    use IndexQueueDeleteRepositoryCreateTrait;
    use IndexQueueReadRepositoryCreateTrait;
    use SearchIndexManagerCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        $instance = self::_new(self::class);
        return $instance;
    }

    /**
     * Add entity to queue
     *
     * @param int $entityType
     * @param int|int[]|null $entityIds Entity Id or array of Ids. null means - entity id is absent
     */
    public function add(int $entityType, int|array|null $entityIds): void
    {
        if (!$this->isIndexing()) {
            return;
        }
        if ($entityIds) {
            if (!is_array($entityIds)) {
                $entityIds = [$entityIds];
            }
            $query = '';
            foreach ($entityIds as $entityId) {
                if ($query) {
                    $query .= ', ';
                }
                $query .= '(' . $this->escape($entityType) . ', ' .
                    $this->escape($entityId) . ')';
            }
            $query = "REPLACE `index_queue` " .
                "(`entity_type`, `entity_id`) VALUES " . $query;
            $this->nonQuery($query);
        }
    }

    /**
     * Remove entity from queue
     *
     * @param int $entityType
     * @param int $entityId
     */
    public function remove(int $entityType, int $entityId): void
    {
        if (!$this->isIndexing()) {
            return;
        }

        $this->createIndexQueueDeleteRepository()
            ->filterEntityType($entityType)
            ->filterEntityId($entityId)
            ->delete();
    }

    /**
     * Operate queued entries
     * @param int $maxExecTime max execution time in seconds
     * @param int $editorUserId
     * @return int
     */
    public function process(int $maxExecTime, int $editorUserId): int
    {
        if (!$this->isIndexing()) {
            return 0;
        }

        $searchIndexManager = $this->createSearchIndexManager();
        $execStartTime = time();
        $successCount = 0;
        while (true) {
            $indexQueues = $this->createIndexQueueReadRepository()
                ->orderByModifiedOn()
                ->limit(100)
                ->loadEntities();
            if (empty($indexQueues)) {
                return $successCount;
            }
            foreach ($indexQueues as $indexQueue) {
                $searchIndexManager->refresh($indexQueue->EntityType, $indexQueue->EntityId, $editorUserId);
                $this->remove($indexQueue->EntityType, $indexQueue->EntityId);
                $successCount++;
                if (time() > $execStartTime + $maxExecTime) {
                    return $successCount;
                }
            }
            if (time() > $execStartTime + $maxExecTime) {
                return $successCount;
            }
        }
    }

    /**
     * Add all active lot items to queue
     */
    public function addAllLotItems(): void
    {
        if (!$this->isIndexing()) {
            return;
        }

        $select = "SELECT '" . Constants\Search::ENTITY_LOT_ITEM . "', id FROM lot_item " .
            "WHERE active";
        $replace = "REPLACE `index_queue` (entity_type, entity_id) " . $select;
        $this->nonQuery($replace);
    }

    /**
     * Add all invoices to queue
     *
     * @param int|null $accountId
     * @param int|null $userId
     */
    public function addAllInvoices(?int $accountId = null, ?int $userId = null): void
    {
        if (!$this->isIndexing()) {
            return;
        }

        $accountCond = $accountId ? " AND account_id = " . $this->escape($accountId) : '';
        $accountCond .= $userId ? " AND bidder_id = " . $this->escape($userId) : '';
        $invoiceStatusList = implode(',', Constants\Invoice::$availableInvoiceStatuses);
        $select = "SELECT '" . Constants\Search::ENTITY_INVOICE . "', id FROM invoice " .
            "WHERE invoice_status_id IN ({$invoiceStatusList})" . $accountCond;
        $replace = "REPLACE `index_queue` (entity_type, entity_id) " . $select;
        $this->nonQuery($replace);
    }

    /**
     * Check, if search indexing is enabled
     * @return bool
     */
    protected function isIndexing(): bool
    {
        return $this->cfg()->get('core->search->index->type') !== Constants\Search::INDEX_NONE;
    }
}
