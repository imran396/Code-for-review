<?php
/**
 * SAM-10948: Stacked Tax. Invoice Management pages. Prepare Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Produce\Internal\BulkGroup;

use LotItem;
use QMySqliDatabaseException;
use RuntimeException;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

class BulkGroupLotItemCollector extends CustomizableClass
{
    use AuctionLotLoaderAwareTrait;
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Find bulk group lots for bulk master and add them to array
     * @param LotItem[] $lotItems
     * @return LotItem[]
     */
    public function addBulkItems(array $lotItems): array
    {
        if (!$lotItems) {
            return [];
        }

        $resultLotItems = [];
        foreach ($lotItems as $lotItem) {
            $resultLotItems[] = $lotItem;
            if (!$lotItem->hasSaleSoldAuction()) {
                continue;
            }

            $auctionLot = $this->getAuctionLotLoader()->load($lotItem->Id, $lotItem->AuctionId);
            if (
                $auctionLot
                && $auctionLot->hasMasterRole()
            ) {
                $db = $this->getDb();
                // @formatter:off
                $query =
                    'SELECT li.* ' .
                    'FROM auction_lot_item ali ' .
                    'INNER JOIN lot_item li ON li.id = ali.lot_item_id AND li.active ' .
                    'WHERE ali.bulk_master_id = ' . $db->SqlVariable($auctionLot->Id) . ' ' .
                        'AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$wonLotStatuses) . ') ' .
                        'AND li.hammer_price = 0 ' .
                        'AND li.winning_bidder_id IS NOT NULL ' .
                        'AND (SELECT count(1) FROM invoice_item AS ii ' .
                            'INNER JOIN invoice AS i on ii.invoice_id = i.id ' .
                            'WHERE ii.lot_item_id = li.id AND ii.active = true ' .
                            'AND ((i.invoice_status_id IN (' .
                                implode(',', Constants\Invoice::$openInvoiceStatuses) .
                            ') AND ii.release = false) ' .
                                'OR (i.invoice_status_id = ' . Constants\Invoice::IS_CANCELED . ' ' .
                                    'AND (ii.winning_bidder_id = li.winning_bidder_id AND ii.auction_id = li.auction_id) ' .
                                    'AND ii.release = false ) ' .
                                ') ' .
                            'AND ii.hammer_price = 0) = 0 ';
                // @formatter:on
                try {
                    $dbResult = $db->Query($query);
                } catch (QMySqliDatabaseException $e) {
                    log_error($e->getCode() . ' - ' . $e->getMessage());
                    throw new RuntimeException($e->getMessage(), $e->getCode());
                }
                $bulkItems = LotItem::InstantiateDbResult($dbResult);
                foreach ($bulkItems as $bulkItem) {
                    $resultLotItems[] = $bulkItem;
                }
            }
        }
        /**
         * Bulk lot items may already present among input lot items, thus we should remove duplicates. (SAM-9524)
         * Invoicing of duplicated items will fail invoice creation and roll-back DB transaction (See, InvoiceChunkGenerator::storeCurrentInvoice())
         */
        $resultLotItems = ArrayHelper::uniqueEntities($resultLotItems, 'Id');
        return $resultLotItems;
    }

}
