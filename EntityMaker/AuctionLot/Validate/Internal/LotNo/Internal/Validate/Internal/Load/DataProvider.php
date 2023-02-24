<?php
/**
 * SAM-8892: Auction Lot entity maker - extract lot# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo\Internal\Validate\Internal\Load;

use Sam\AuctionLot\Validate\AuctionLotExistenceChecker;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\LotFieldConfig\Provider\LotFieldConfigProviderAwareTrait;

/**
 * Class DataProvider
 * @package Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo\Internal\Validate\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use LotFieldConfigProviderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param int $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param array $skipAuctionLotIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByLotNum(
        int $auctionId,
        int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        array $skipAuctionLotIds = [],
        bool $isReadOnlyDb = false
    ): bool {
        return AuctionLotExistenceChecker::new()->existByLotNo(
            $auctionId,
            $lotNum,
            $lotNumExt,
            $lotNumPrefix,
            $skipAuctionLotIds,
            $isReadOnlyDb
        );
    }

    /**
     * Check if lotNum is marked as required
     *
     * @param int $accountId
     * @return bool
     */
    public function isRequiredByLotFieldConfig(int $accountId): bool
    {
        $isRequired = $this->getLotFieldConfigProvider()->isRequired(
            Constants\LotFieldConfig::LOT_NUMBER,
            $accountId
        );
        return $isRequired;
    }
}
