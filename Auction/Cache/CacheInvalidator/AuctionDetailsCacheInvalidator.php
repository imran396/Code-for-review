<?php
/**
 * SAM-6292: Move fields from auction_cache to auction_details_cache
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache\CacheInvalidator;


use Sam\Core\Service\CustomizableClass;
use Sam\Core\DataSource\DbConnectionTrait;

/**
 * Class AuctionDetailsCacheInvalidator
 * @package Sam\Auction\Cache\CacheInvalidator
 */
class AuctionDetailsCacheInvalidator extends CustomizableClass
{
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Clear value of auction_details_cache.calculated_on
     * It invalidates only when filtering condition is not empty.
     *
     * @param array $keys
     * @param CacheInvalidatorFilterCondition $condition
     * @param int $editorUserId
     */
    public function invalidate(array $keys, CacheInvalidatorFilterCondition $condition, int $editorUserId): void
    {
        if (
            !$keys
            || $condition->isEmpty()
        ) {
            log_debug('Auction details cache invalidation skipped, because of empty filtering condition');
            return;
        }

        $whereClause = $this->buildWhereClause($keys, $condition);
        $this->dropCalculatedOn($whereClause, $editorUserId);
        log_trace('Auction details cache invalidated for keys: ' . implode(', ', $keys));
    }

    /**
     * @param array $keys
     * @param CacheInvalidatorFilterCondition $condition
     * @return string
     */
    private function buildWhereClause(array $keys, CacheInvalidatorFilterCondition $condition): string
    {
        $escapedKeys = array_map([$this, 'escape'], $keys);
        $filterConditions = [
            sprintf('adc.auction_id IN (%s)', $condition->buildExpression()),
            sprintf('adc.`key` IN (%s)', implode(', ', $escapedKeys)),
            'adc.calculated_on IS NOT NULL'
        ];

        $whereClause = implode(' AND ', $filterConditions);
        return $whereClause;
    }

    /**
     * @param string $whereClause
     * @param int $editorUserId
     */
    private function dropCalculatedOn(string $whereClause, int $editorUserId): void
    {
        $query = "UPDATE auction_details_cache adc"
            . " SET adc.calculated_on = NULL,"
            . " adc.modified_by = " . $this->escape($editorUserId)
            . " WHERE {$whereClause}";
        $this->nonQuery($query);
    }
}
