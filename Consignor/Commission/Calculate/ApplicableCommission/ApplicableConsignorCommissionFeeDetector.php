<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Calculate\ApplicableCommission;

use Sam\Consignor\Commission\Calculate\ApplicableCommission\Internal\Load\DataProvider;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;

/**
 * This class contains methods that allow detecting commission and fee rule id
 * that may be stored on lot item, auction lot, auction, user, or account level.
 *
 * Class ApplicableConsignorCommissionFeeDetector
 * @package Sam\Consignor\Commission\Calculate
 */
class ApplicableConsignorCommissionFeeDetector extends CustomizableClass
{
    use LotItemLoaderAwareTrait;

    protected ?DataProvider $dataProvider = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load appropriate commission id based on the following hierarchy:
     * i.   lot item level
     * ii.  auction lot level
     * iii. auction level
     * iv.  consignor-account level (look up commission and fees consignor in account)
     * v.   account level
     * @param int $lotItemId
     * @param int|null $auctionId
     * @param int $consignorUserId
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function detectCommissionId(
        int $lotItemId,
        ?int $auctionId,
        int $consignorUserId,
        int $accountId,
        bool $isReadOnlyDb = false
    ): ?int {
        $dataProvider = $this->createDataProvider();
        $commissionId = $dataProvider->loadAuctionLotLevelActiveCommissionId($lotItemId, $auctionId, $isReadOnlyDb)
            ?? $dataProvider->loadLotItemLevelActiveCommissionId($lotItemId, $isReadOnlyDb)
            ?? $dataProvider->loadAuctionLevelActiveCommissionId($auctionId, $isReadOnlyDb)
            ?? $dataProvider->loadUserLevelActiveCommissionId($consignorUserId, $accountId, $isReadOnlyDb)
            ?? $dataProvider->loadAccountLevelActiveCommissionId($accountId);

        return $commissionId;
    }

    /**
     * Load appropriate sold fee id based on the following hierarchy:
     * i.   lot item level
     * ii.  auction lot level
     * iii. auction level
     * iv.  consignor-account level (look up commission and fees consignor in account)
     * v.   account level
     * @param int $lotItemId
     * @param int|null $auctionId
     * @param int $consignorUserId
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function detectSoldFeeId(
        int $lotItemId,
        ?int $auctionId,
        int $consignorUserId,
        int $accountId,
        bool $isReadOnlyDb = false
    ): ?int {
        $dataProvider = $this->createDataProvider();
        $commissionId = $dataProvider->loadLotItemLevelActiveSoldFeeId($lotItemId, $isReadOnlyDb)
            ?? $dataProvider->loadAuctionLotLevelActiveSoldFeeId($lotItemId, $auctionId, $isReadOnlyDb)
            ?? $dataProvider->loadAuctionLevelActiveSoldFeeId($auctionId, $isReadOnlyDb)
            ?? $dataProvider->loadUserLevelActiveSoldFeeId($consignorUserId, $accountId, $isReadOnlyDb)
            ?? $dataProvider->loadAccountLevelActiveSoldFeeId($accountId);

        return $commissionId;
    }

    /**
     * Load appropriate unsold fee id based on the following hierarchy:
     * i.   lot item level
     * ii.  auction lot level
     * iii. auction level
     * iv.  consignor-account level (look up commission and fees consignor in account)
     * v.   account level
     * @param int $lotItemId
     * @param int|null $auctionId
     * @param int $consignorUserId
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function detectUnsoldFeeId(
        int $lotItemId,
        ?int $auctionId,
        int $consignorUserId,
        int $accountId,
        bool $isReadOnlyDb = false
    ): ?int {
        $dataProvider = $this->createDataProvider();
        $commissionId = $dataProvider->loadLotItemLevelActiveUnsoldFeeId($lotItemId, $isReadOnlyDb)
            ?? $dataProvider->loadAuctionLotLevelActiveUnsoldFeeId($lotItemId, $auctionId, $isReadOnlyDb)
            ?? $dataProvider->loadActiveAuctionLevelUnsoldFeeId($auctionId, $isReadOnlyDb)
            ?? $dataProvider->loadActiveUserLevelUnsoldFeeId($consignorUserId, $accountId, $isReadOnlyDb)
            ?? $dataProvider->loadAccountLevelActiveUnsoldFeeId($accountId);
        return $commissionId;
    }

    protected function createDataProvider(): DataProvider
    {
        return $this->dataProvider ?: DataProvider::new();
    }

    /**
     * @param DataProvider $dataProvider
     * @return static
     * @internal
     */
    public function setDataProvider(DataProvider $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }
}
