<?php
/**
 * SAM-9667: Refactor invoice generation module for v3-6
 * SAM-4377: Invoice producer
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01.08.2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\StackedTax\Generate\Chunk\Internal\Load;

use Invoice;
use QMySqli5DatabaseResult;
use QMySqliDatabaseException;
use RuntimeException;
use Sam\Auction\Load\AuctionLoader;
use Sam\Auction\SaleGroup\SaleGroupManager;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Invoice\Common\Load\InvoiceLoader;
use Sam\Invoice\Common\Load\PreInvoicingDataLoaderCreateTrait;

/**
 * Class DataLoader
 * @package Sam\Invoice\StackedTax\Generate
 */
class DataProvider extends CustomizableClass
{
    use DbConnectionTrait;
    use PreInvoicingDataLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Loads list of lots that not included in any invoice yet
     * @param int $accountId
     * @param int|null $auctionId - null means that we retrieve items for all auctions
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadBillableItemsInvoiced(
        int $accountId,
        int $auctionId = null,
        bool $isReadOnlyDb = false
    ): array {
        $db = $this->getDb();
        $openInvoiceStatuses = implode(',', Constants\Invoice::$openInvoiceStatuses);
        $availableLotStatuses = implode(',', Constants\Lot::$availableLotStatuses);
        $sql = <<<SQL
SELECT
  ii.lot_name,
  ii.lot_item_id,
  ii.invoice_id,
  li.auction_id AS target_auction_id,
  i.invoice_no  AS invoice_no,
  li.item_num,
  li.item_num_ext,
  ali.id as auction_lot_id,
  ali.lot_num,
  ali.lot_num_ext,
  ali.lot_num_prefix,
  a.sale_num,
  a.sale_num_ext
FROM lot_item as li
INNER JOIN invoice_item as ii ON ii.lot_item_id = li.id
INNER JOIN invoice as i ON ii.invoice_id = i.id
LEFT JOIN auction_lot_item ali ON li.id = ali.lot_item_id 
  AND ali.lot_status_id in ({$availableLotStatuses}) 
  AND li.auction_id = ali.auction_id
LEFT JOIN auction a ON li.auction_id = a.id
WHERE (i.bidder_id != li.winning_bidder_id OR ii.auction_id != li.auction_id)
  AND ii.active = true
  AND ii.hammer_price IS NOT NULL
  AND ii.release = false
  AND i.invoice_status_id IN ({$openInvoiceStatuses})
  AND li.winning_bidder_id IS NOT NULL
  AND li.hammer_price IS NOT NULL
  AND li.account_id = {$this->escape($accountId)}
SQL;
        if ($auctionId) {
            $sql .= " AND ii.auction_id = {$this->escape($auctionId)}";
        }

        try {
            $dbResult = $db->Query($sql, $isReadOnlyDb);
        } catch (QMySqliDatabaseException $e) {
            log_error($e->getCode() . ' - ' . $e->getMessage());
            throw new RuntimeException($e->getMessage(), $e->getCode());
        }
        $invoiceItems = [];
        while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $invoiceItems[] = $row;
        }
        return $invoiceItems;
    }

    public function loadAuctionRow(?int $auctionId, bool $isReadOnlyDb = false): array
    {
        $auctionRow = AuctionLoader::new()->loadSelected(['sale_group, account_id'], $auctionId, $isReadOnlyDb);
        return [
            $auctionRow['sale_group'] ?? '',
            Cast::toInt($auctionRow['account_id'] ?? null)
        ];
    }

    public function loadSaleGroupAuctionIds(
        string $saleGroup,
        int $accountId,
        bool $isReadOnlyDb = false
    ): array {
        $saleGroupAuctionRows = SaleGroupManager::new()->loadSelected(
            ['id'],
            $saleGroup,
            $accountId,
            false,
            [],
            $isReadOnlyDb
        );
        $auctionIds = ArrayCast::arrayColumnInt($saleGroupAuctionRows, 'id');
        return $auctionIds;
    }

    /**
     * @return Invoice[]
     */
    public function loadInvoices(array $invoiceIds, bool $isReadOnlyDb = false): array
    {
        return InvoiceLoader::new()->loadByIds($invoiceIds, $isReadOnlyDb);
    }

    public function loadPreInvoicingWinningUserIdsNew(
        int $accountId,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        array $auctionIds = [],
        bool $isReadOnlyDb = false
    ): array {
        return $this->createPreInvoicingDataLoader()->loadWinningUserIdsNew(
            $accountId,
            $startDateSysIso,
            $endDateSysIso,
            $auctionIds,
            $isReadOnlyDb
        );
    }

    public function loadPreInvoicingWinningUserIds(
        int $accountId,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        array $auctionIds = [],
        bool $isReadOnlyDb = false
    ): array {
        return $this->createPreInvoicingDataLoader()->loadWinningUserIds(
            $accountId,
            $startDateSysIso,
            $endDateSysIso,
            $auctionIds,
            $isReadOnlyDb
        );
    }

    public function loadPreInvoicingLotItemsNew(
        int $accountId,
        ?int $winningUserId = null,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        array $auctionIds = [],
        bool $isReadOnlyDb = false
    ): array {
        return $this->createPreInvoicingDataLoader()->loadLotItemsNew(
            $accountId,
            $winningUserId,
            $startDateSysIso,
            $endDateSysIso,
            $auctionIds,
            $isReadOnlyDb
        );
    }

    public function loadPreInvoicingLotItems(
        int $accountId,
        ?int $winningUserId = null,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        array $auctionIds = [],
        bool $isReadOnlyDb = false
    ): array {
        return $this->createPreInvoicingDataLoader()->loadLotItems(
            $accountId,
            $winningUserId,
            $startDateSysIso,
            $endDateSysIso,
            $auctionIds,
            $isReadOnlyDb
        );
    }
}
