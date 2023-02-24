<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 05, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\Lot\Internal;

use Sam\Api\GraphQL\Load\Internal\Lot\AuctionLotIdentifier;

/**
 * Class AuctionLotDataSourceMysql
 * @package Sam\Api\GraphQL\Load\Internal\Lot\Internal
 */
class AuctionLotDataSourceMysql extends \Sam\Core\Lot\LotList\DataSourceMysql
{
    /** @var AuctionLotIdentifier[] */
    protected array $filterIdentifiers = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionLotIdentifier[] $identifiers
     * @return static
     */
    public function filterIdentifier(array $identifiers): static
    {
        $this->filterIdentifiers = $identifiers;
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * Define mapping for result fields
     * @param array $queryParts
     * @return array
     */
    protected function initializeFilterQueryParts(array $queryParts = []): array
    {
        $identifierFilterExpressions = [];
        foreach ($this->filterIdentifiers as $identifier) {
            $identifierFilterExpressions[] = "(ali.lot_item_id = {$identifier->lotItemId} AND ali.auction_id = {$identifier->auctionId})";
        }
        $queryParts['where'][] = implode(' OR ', $identifierFilterExpressions);

        $queryParts = parent::initializeFilterQueryParts($queryParts);
        return $queryParts;
    }
}
