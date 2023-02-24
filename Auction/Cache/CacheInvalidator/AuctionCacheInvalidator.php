<?php
/**
 * SAM-6041: Extract auction cache calculated timestamp drop to separate class
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           4/30/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache\CacheInvalidator;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\DataSource\DbConnectionTrait;

/**
 * Class AuctionCacheInvalidator
 * @package Sam\Auction\Cache
 */
class AuctionCacheInvalidator extends CustomizableClass
{
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Clear value of auction_cache.calculated_on
     * It invalidates only when filtering condition is required.
     * It doesn't invalidate all, thus we can avoid condition argument checking in caller.
     * @param CacheInvalidatorFilterCondition $condition
     * @param int $editorUserId
     */
    public function invalidate(CacheInvalidatorFilterCondition $condition, int $editorUserId): void
    {
        if ($condition->isEmpty()) {
            log_debug("Auction cache invalidation skipped, because of empty filtering condition");
            return;
        }

        $conditions = [
            sprintf('ac.auction_id IN (%s)', $condition->buildExpression()),
            "ac.calculated_on IS NOT NULL"
        ];

        $this->update($conditions, $editorUserId);
    }

    /**
     * Invalidate all caches
     * @param int $editorUserId
     */
    public function invalidateAll(int $editorUserId): void
    {
        $this->update(["ac.calculated_on IS NOT NULL"], $editorUserId);
    }

    /**
     * @param array $conditions
     * @param int $editorUserId
     */
    private function update(array $conditions, int $editorUserId): void
    {
        $whereClause = " WHERE " . implode(' AND ', $conditions);
        $query = "SELECT ac.calculated_on AS `calculated_on` FROM auction_cache ac" . $whereClause;
        $this->query($query);
        $row = $this->fetchAssoc();
        if ($row && $row['calculated_on']) {
            $query = "UPDATE auction_cache ac"
                . " SET ac.calculated_on = NULL,"
                . " ac.modified_by = " . $this->escape($editorUserId)
                . $whereClause;
            $this->nonQuery($query);
        }
    }
}
