<?php
/**
 * SAM-10097: Distinguish auction bidder autocomplete data loading end-points for different pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder\Internal\Build\Internal\Load;

use Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder\Internal\Build\Internal\Load\Internal\Load\DataLoader;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoader;

/**
 * Class DataProvider
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder\Internal\Build\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load winning auction assigned to lot item.
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function loadWinningAuctionId(int $lotItemId, bool $isReadOnlyDb = false): ?int
    {
        $row = LotItemLoader::new()->loadSelected(['li.auction_id'], $lotItemId, $isReadOnlyDb);
        return Cast::toInt($row['auction_id'] ?? null);
    }

    /**
     * Load filtered data for auto-completer list response.
     * @param string $searchKeyword
     * @param int|null $filterAuctionId
     * @param int|null $contextAuctionId
     * @param int|null $filterAccountId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadResponseData(
        string $searchKeyword,
        ?int $filterAuctionId,
        ?int $contextAuctionId,
        ?int $filterAccountId,
        bool $isReadOnlyDb = false
    ): array {
        return DataLoader::new()->load(
            $searchKeyword,
            $filterAuctionId,
            $contextAuctionId,
            $filterAccountId,
            $isReadOnlyDb
        );
    }
}
