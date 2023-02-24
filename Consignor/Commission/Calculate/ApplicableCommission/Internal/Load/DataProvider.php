<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Calculate\ApplicableCommission\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManager;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\ConsignorCommissionFee\ConsignorCommissionFeeReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\UserConsignorCommissionFee\UserConsignorCommissionFeeReadRepository;

/**
 * Internal data provider for ApplicableConsignorCommissionFeeDetector
 *
 * Class DataLoader
 * @package Sam\Consignor\Commission\Calculate\ApplicableCommission\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadLotItemLevelActiveCommissionId(int $lotItemId, bool $isReadOnlyDb = false): ?int
    {
        $row = LotItemReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($lotItemId)
            ->joinAccountFilterActive(true)
            ->joinConsignorCommissionFilterActive(true)
            ->select(['li.consignor_commission_id'])
            ->loadRow();
        return Cast::toInt($row['consignor_commission_id'] ?? null);
    }

    public function loadAuctionLotLevelActiveCommissionId(int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        $row = AuctionLotItemReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->filterLotItemId($lotItemId)
            ->filterAuctionId($auctionId)
            ->joinAccountFilterActive(true)
            ->joinConsignorCommissionFilterActive(true)
            ->select(['ali.consignor_commission_id'])
            ->loadRow();
        return Cast::toInt($row['consignor_commission_id'] ?? null);
    }

    public function loadAuctionLevelActiveCommissionId(?int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        if (!$auctionId) {
            return null;
        }

        $row = AuctionReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->filterId($auctionId)
            ->joinAccountFilterActive(true)
            ->joinConsignorCommissionFilterActive(true)
            ->select(['a.consignor_commission_id'])
            ->loadRow();
        return Cast::toInt($row['consignor_commission_id'] ?? null);
    }

    public function loadUserLevelActiveCommissionId(int $userId, int $accountId, bool $isReadOnlyDb = false): ?int
    {
        $row = UserConsignorCommissionFeeReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterUserId($userId)
            ->filterAccountId($accountId)
            ->joinAccountFilterActive(true)
            ->joinConsignorCommissionFilterActive(true)
            ->select(['uccf.commission_id'])
            ->loadRow();
        return Cast::toInt($row['commission_id'] ?? null);
    }

    public function loadAccountLevelActiveCommissionId(int $accountId, bool $isReadOnlyDb = false): ?int
    {
        $commissionId = SettingsManager::new()->get(Constants\Setting::CONSIGNOR_COMMISSION_ID, $accountId);
        if ($this->isConsignorCommissionFeeExist($commissionId, $isReadOnlyDb)) {
            return $commissionId;
        }
        return null;
    }

    public function loadLotItemLevelActiveSoldFeeId(int $lotItemId, bool $isReadOnlyDb = false): ?int
    {
        $row = LotItemReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($lotItemId)
            ->joinAccountFilterActive(true)
            ->joinConsignorSoldFeeFilterActive(true)
            ->select(['li.consignor_sold_fee_id'])
            ->loadRow();
        return Cast::toInt($row['consignor_sold_fee_id'] ?? null);
    }

    public function loadAuctionLotLevelActiveSoldFeeId(int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        $row = AuctionLotItemReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->filterLotItemId($lotItemId)
            ->filterAuctionId($auctionId)
            ->joinAccountFilterActive(true)
            ->joinConsignorSoldFeeFilterActive(true)
            ->select(['ali.consignor_sold_fee_id'])
            ->loadRow();
        return Cast::toInt($row['consignor_sold_fee_id'] ?? null);
    }

    public function loadAuctionLevelActiveSoldFeeId(?int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        if (!$auctionId) {
            return null;
        }

        $row = AuctionReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->filterId($auctionId)
            ->joinAccountFilterActive(true)
            ->joinConsignorSoldFeeFilterActive(true)
            ->select(['a.consignor_sold_fee_id'])
            ->loadRow();
        return Cast::toInt($row['consignor_sold_fee_id'] ?? null);
    }

    public function loadUserLevelActiveSoldFeeId(int $userId, int $accountId, bool $isReadOnlyDb = false): ?int
    {
        $row = UserConsignorCommissionFeeReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterUserId($userId)
            ->filterAccountId($accountId)
            ->joinAccountFilterActive(true)
            ->joinConsignorSoldFeeFilterActive(true)
            ->select(['uccf.sold_fee_id'])
            ->loadRow();
        return Cast::toInt($row['sold_fee_id'] ?? null);
    }

    public function loadAccountLevelActiveSoldFeeId(int $accountId, bool $isReadOnlyDb = false): ?int
    {
        $soldFeeId = SettingsManager::new()->get(Constants\Setting::CONSIGNOR_SOLD_FEE_ID, $accountId);
        if ($this->isConsignorCommissionFeeExist($soldFeeId, $isReadOnlyDb)) {
            return $soldFeeId;
        }
        return null;
    }

    public function loadLotItemLevelActiveUnsoldFeeId(int $lotItemId, bool $isReadOnlyDb = false): ?int
    {
        $row = LotItemReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($lotItemId)
            ->joinAccountFilterActive(true)
            ->joinConsignorUnsoldFeeFilterActive(true)
            ->select(['li.consignor_unsold_fee_id'])
            ->loadRow();
        return Cast::toInt($row['consignor_unsold_fee_id'] ?? null);
    }

    public function loadAuctionLotLevelActiveUnsoldFeeId(int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        $row = AuctionLotItemReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->filterLotItemId($lotItemId)
            ->filterAuctionId($auctionId)
            ->joinAccountFilterActive(true)
            ->joinConsignorUnsoldFeeFilterActive(true)
            ->select(['ali.consignor_unsold_fee_id'])
            ->loadRow();
        return Cast::toInt($row['consignor_unsold_fee_id'] ?? null);
    }

    public function loadActiveAuctionLevelUnsoldFeeId(?int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        if (!$auctionId) {
            return null;
        }

        $row = AuctionReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->filterId($auctionId)
            ->joinAccountFilterActive(true)
            ->joinConsignorUnsoldFeeFilterActive(true)
            ->select(['a.consignor_unsold_fee_id'])
            ->loadRow();
        return Cast::toInt($row['consignor_unsold_fee_id'] ?? null);
    }

    public function loadActiveUserLevelUnsoldFeeId(int $userId, int $accountId, bool $isReadOnlyDb = false): ?int
    {
        $row = UserConsignorCommissionFeeReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterUserId($userId)
            ->filterAccountId($accountId)
            ->joinAccountFilterActive(true)
            ->joinConsignorUnsoldFeeFilterActive(true)
            ->select(['uccf.unsold_fee_id'])
            ->loadRow();
        return Cast::toInt($row['unsold_fee_id'] ?? null);
    }

    public function loadAccountLevelActiveUnsoldFeeId(int $accountId, bool $isReadOnlyDb = false): ?int
    {
        $unsoldFeeId = SettingsManager::new()->get(Constants\Setting::CONSIGNOR_UNSOLD_FEE_ID, $accountId);
        if ($this->isConsignorCommissionFeeExist($unsoldFeeId, $isReadOnlyDb)) {
            return $unsoldFeeId;
        }
        return null;
    }

    protected function isConsignorCommissionFeeExist(?int $id, bool $isReadOnlyDb = false): bool
    {
        if (!$id) {
            return false;
        }
        $isExist = ConsignorCommissionFeeReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($id)
            ->exist();
        return $isExist;
    }
}
