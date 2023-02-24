<?php
/**
 * SAM-11972: Stacked Tax. Geo Type field at Tax Schema is still editable even when Tax Schema assigned to different entity(lot,auction,account,location)
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 12, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Validate\Assignment\Internal\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepositoryCreateTrait;

/**
 * Class DataProvider
 * @package Sam\Tax\StackedTax\Schema\Validate\Assignment\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use AuctionReadRepositoryCreateTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use LotItemReadRepositoryCreateTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isAssignedToLotItems(
        int $taxSchemaId,
        int $amountSource,
        int $accountId,
        bool $isReadOnlyDb = false
    ): bool {
        $repo = $this->createLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->joinAccountFilterActive(true)
            ->filterActive(true);
        if ($amountSource === Constants\StackedTax::AS_HAMMER_PRICE) {
            $repo->filterHpTaxSchemaId($taxSchemaId);
        } elseif ($amountSource === Constants\StackedTax::AS_BUYERS_PREMIUM) {
            $repo->filterBpTaxSchemaId($taxSchemaId);
        }
        return $repo->exist();
    }

    public function isAssignedToAuctionLotItems(
        int $taxSchemaId,
        int $amountSource,
        int $accountId,
        bool $isReadOnlyDb = false
    ): bool {
        $repo = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses) // TODO: Archived?
            ->joinLotItemFilterActive(true);
        if ($amountSource === Constants\StackedTax::AS_HAMMER_PRICE) {
            $repo->filterHpTaxSchemaId($taxSchemaId);
        } elseif ($amountSource === Constants\StackedTax::AS_BUYERS_PREMIUM) {
            $repo->filterBpTaxSchemaId($taxSchemaId);
        }
        return $repo->exist();
    }

    public function isAssignedToAuctions(
        int $taxSchemaId,
        int $amountSource,
        int $accountId,
        bool $isReadOnlyDb = false
    ): bool {
        $repo = $this->createAuctionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterAuctionStatusId(Constants\Auction::$availableAuctionStatuses) // TODO: Archived?
            ->joinAccountFilterActive(true)
            ->joinLotItemFilterActive(true);
        if ($amountSource === Constants\StackedTax::AS_HAMMER_PRICE) {
            $repo->filterHpTaxSchemaId($taxSchemaId);
        } elseif ($amountSource === Constants\StackedTax::AS_BUYERS_PREMIUM) {
            $repo->filterBpTaxSchemaId($taxSchemaId);
        }
        return $repo->exist();
    }

    /**
     * @param int $accountId
     * @return int|null null when tax schema is not defined at account level
     */
    public function loadHpTaxSchemaForAccount(int $accountId): ?int
    {
        $invoiceHpTaxSchemaId = $this->getSettingsManager()->get(Constants\Setting::INVOICE_HP_TAX_SCHEMA_ID, $accountId);
        return Cast::toInt($invoiceHpTaxSchemaId);
    }

    /**
     * @param int $accountId
     * @return int|null null when tax schema is not defined at account level
     */
    public function loadBpTaxSchemaForAccount(int $accountId): ?int
    {
        $invoiceBpTaxSchemaId = $this->getSettingsManager()->get(Constants\Setting::INVOICE_BP_TAX_SCHEMA_ID, $accountId);
        return Cast::toInt($invoiceBpTaxSchemaId);
    }
}
