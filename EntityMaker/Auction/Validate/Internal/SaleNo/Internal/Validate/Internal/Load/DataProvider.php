<?php
/**
 * SAM-8891: Auction entity-maker - Extract sale# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\SaleNo\Internal\Validate\Internal\Load;

use Sam\Auction\SaleNo\SaleNoAdviserAwareTrait;
use Sam\Auction\Validate\AuctionExistenceCheckerAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\EntityMaker\Auction\Validate\Internal\SaleNo\Internal\Validate\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use AuctionExistenceCheckerAwareTrait;
    use SaleNoAdviserAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }


    /**
     * @param int $saleNum
     * @param string $saleNumExt
     * @param array $skipAuctionIds
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existBySaleNo(
        int $saleNum,
        string $saleNumExt,
        array $skipAuctionIds,
        ?int $accountId,
        bool $isReadOnlyDb = false
    ): bool {
        return $this->getAuctionExistenceChecker()->existBySaleNo(
            $saleNum,
            $saleNumExt,
            $skipAuctionIds,
            $accountId,
            $isReadOnlyDb
        );
    }

    /**
     * @param int $accountId
     * @return int|null
     */
    public function suggestSaleNo(int $accountId): ?int
    {
        return $this->getSaleNoAdviser()->suggest($accountId);
    }
}
