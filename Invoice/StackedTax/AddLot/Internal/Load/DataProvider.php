<?php
/**
 * SAM-10824: Stacked Tax. Tax calculation on invoicing
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\AddLot\Internal\Load;

use LotItem;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoader;
use Sam\Invoice\Common\Load\PreInvoicingDataLoader;
use Sam\Settings\SettingsManager;

/**
 * Class DataProvider
 * @package Sam\Invoice\StackedTax\AddLot\Internal\Load
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
     * @param int $accountId
     * @param int|null $winningUserId
     * @param string|null $startDateSysIso
     * @param string|null $endDateSysIso
     * @param array $auctionIds
     * @param bool $isReadOnlyDb
     * @return LotItem[]
     */
    public function loadPreInvoicingLotItems(
        int $accountId,
        ?int $winningUserId = null,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        array $auctionIds = [],
        bool $isReadOnlyDb = false
    ): array {
        return PreInvoicingDataLoader::new()->loadLotItems(
            $accountId,
            $winningUserId,
            $startDateSysIso,
            $endDateSysIso,
            $auctionIds,
            $isReadOnlyDb
        );
    }

    /**
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadInvoicedAuctionIds(int $invoiceId, bool $isReadOnlyDb = false): array
    {
        $invoicedAuctionDtos = InvoiceItemLoader::new()->loadInvoicedAuctionDtos($invoiceId, $isReadOnlyDb);
        $invoicedAuctionIds = ArrayCast::arrayColumnInt($invoicedAuctionDtos, 'auctionId');
        return $invoicedAuctionIds;
    }

    public function isMultipleSaleInvoice(int $accountId): bool
    {
        return (bool)SettingsManager::new()->get(Constants\Setting::MULTIPLE_SALE_INVOICE, $accountId);
    }
}
