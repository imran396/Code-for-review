<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           26.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Common\Load;

use QMySqli5DatabaseResult;
use QMySqliDatabaseException;
use RuntimeException;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Location\Load\LocationLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\Location\LocationReadRepositoryCreateTrait;

/**
 * Class InvoiceHeaderDataLoader
 * @package Sam\Invoice\Common\Load
 */
class InvoiceHeaderDataLoader extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use DbConnectionTrait;
    use InvoiceItemReadRepositoryCreateTrait;
    use LocationLoaderAwareTrait;
    use LocationReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Prepare Location data for invoice header
     *
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function load(int $invoiceId, bool $isReadOnlyDb = false): array
    {
        $invoiceItemRepository = $this->createInvoiceItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterInvoiceId($invoiceId)
            // ->joinAccountFilterActive(true)
            // ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            // ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            // ->joinLotItemFilterActive(true)
            ->joinInvoiceFilterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses)
            // ->joinUserWinningBidderFilterUserStatusId(Constants\User::US_ACTIVE)
            ->groupByAuctionId()
            ->order('ctr', false)
            ->select(
                [
                    'ii.auction_id',
                    'COUNT(ii.auction_id) AS ctr',
                ]
            );
        $row = $invoiceItemRepository->loadRow();

        // TODO: simplify this confusing logic

        if (!isset($row['ctr'])) {
            return [];
        }
        /*
        * Check if it only has one highest item
        */
        $query =
            "SELECT auction_id FROM " .
            "(SELECT auction_id, COUNT(auction_id) AS ctr FROM invoice_item " .
            "WHERE active AND invoice_id = " . $this->escape($invoiceId) . " " .
            "GROUP BY auction_id ORDER BY ctr DESC) AS inv WHERE ctr = " . $row['ctr'];

        try {
            $dbResult = $this->query($query);
        } catch (QMySqliDatabaseException $e) {
            log_error($e->getCode() . ' - ' . $e->getMessage());
            throw new RuntimeException($e->getMessage(), $e->getCode());
        }
        $x = 0;
        while ($dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $x++;
        }
        if ($x > 1) {
            return [];
        }

        $auctionId = (int)$row['auction_id'];
        if ($auctionId) {
            $auction = $this->getAuctionLoader()->load($auctionId);
            $locationRow = $this->getLocationLoader()->loadCommonOrSpecificLocationAsArray(Constants\Location::TYPE_AUCTION_INVOICE, $auction, true);

            if (
                isset($locationRow['logo'])
                || isset($locationRow['address'])
            ) {
                return $locationRow;
            }
        }

        return [];
    }

}
