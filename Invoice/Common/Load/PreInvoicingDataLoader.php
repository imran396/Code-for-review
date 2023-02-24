<?php
/**
 * Loads data required for pre-invoice generation, un-invoiced users, auctions, lots
 *
 * SAM-4668: Pre-invoicing data loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 2, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Load;

use DateTime;
use LotItem;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\User\Render\UserRendererAwareTrait;

/**
 * Class PreInvoicingDataLoader
 * @package Sam\Invoice\Common\Load
 */
class PreInvoicingDataLoader extends CustomizableClass
{
    use DateHelperAwareTrait;
    use DbConnectionTrait;
    use FilesystemCacheManagerAwareTrait;
    use UserRendererAwareTrait;

    private const CACHED_PARAM_LIFE_TIME = 120; // keep parameters alive for 2 mins

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return an array with all winning bidder names billable for invoice
     * username, first name, last name & customer number in
     * active, started, closed and paused auctions.
     * NOT from deleted or archived auctions
     * We always try to use cache
     * @param int|null $accountId filter by account
     * @param string|null $startDateSysIso - start date of search period in system tz
     * @param string|null $endDateSysIso - end date of search period in system tz
     * @param int|null $auctionId - null mean no filtering by auction
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadUserNames(
        ?int $accountId = null,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        ?int $auctionId = null,
        bool $isReadOnlyDb = false
    ): array {
        $cacheKey = implode('_', ['names', $accountId, $startDateSysIso, $endDateSysIso, $auctionId]);
        $cacheManager = $this->getFilesystemCacheManager()
            ->setExtension('txt')
            ->setNamespace('winning_bidder_for_invoicing');
        $winningBidders = $cacheManager->get($cacheKey);
        if (!$winningBidders) {
            $winningBidders = $this->loadUserNamesFromDb(
                $accountId,
                $startDateSysIso,
                $endDateSysIso,
                $auctionId,
                $isReadOnlyDb
            );
            $cacheManager->set($cacheKey, $winningBidders, self::CACHED_PARAM_LIFE_TIME);
        }
        return $winningBidders;
    }

    /**
     * Retrieve Winning Bidder Names pending of invoicing
     * @param int|null $accountId
     * @param string|null $startDateSysIso
     * @param string|null $endDateSysIso
     * @param int|null $auctionId - null means no filtering by auction.
     * TODO: implement filtering by "Not Assigned" li.auction_id
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadUserNamesFromDb(
        ?int $accountId = null,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        ?int $auctionId = null,
        bool $isReadOnlyDb = false
    ): array {
        $n = "\n";
        $auctionBidderSelect = '';
        $auctionBidderJoin = '';
        if ($auctionId) {
            $auctionBidderSelect = "ab.bidder_num AS bidder_num, ";
            $auctionBidderJoin = "LEFT JOIN auction_bidder ab ON ab.user_id = u.id AND ab.auction_id = " . $this->escape($auctionId) . " ";
        }

        $where = $this->buildMutualConditions(
            $accountId,
            $startDateSysIso,
            $endDateSysIso,
            (array)$auctionId
        );

        // @formatter:off
        $query =
            "SELECT DISTINCT " .
                "u.id AS user_id, " .
                "u.username AS username, " .
                "u.customer_no AS customer_no, " .
                "u.email AS email, " .
                "ui.first_name AS first_name, " .
                "ui.last_name AS last_name, " .
                $auctionBidderSelect .
                "CONCAT(IFNULL(TRIM(ui.last_name), ''), IFNULL(TRIM(ui.first_name), ''), u.username) AS bidder_name_ordering " . $n .
            "FROM lot_item AS li " .
            "LEFT JOIN account acc ON acc.id = li.account_id " .
            "LEFT JOIN auction a ON a.id = li.auction_id " .
            // "LEFT JOIN auction_lot_item ali ON ali.auction_id = li.auction_id AND ali.lot_item_id = li.id " .
            "LEFT JOIN user AS u ON u.id = li.winning_bidder_id " .
            "LEFT JOIN user_info AS ui ON ui.user_id = u.id " .
            $auctionBidderJoin .
            "WHERE " . $where . " " . $n .
            "ORDER BY bidder_name_ordering";
        // @formatter:on

        $this->query($query, $isReadOnlyDb);
        $bidderNames = [];
        while ($row = $this->fetchAssoc()) {
            $bidderNum = $row['bidder_num'] ?? null;
            $bidderNames[(int)$row['user_id']] = $this->getUserRenderer()->makeNameLine(
                $row['first_name'],
                $row['last_name'],
                $row['username'],
                $row['email'],
                $row['customer_no'],
                $bidderNum
            );
        }
        return $bidderNames;
    }

    /**
     * @param int|null $accountId optional account filter
     * @param string|null $startDateSysIso optional start date filter
     * @param string|null $endDateSysIso optional end date filter
     * @param int|null $auctionId optional auction filter
     * @return string
     */
    protected function generateCacheKeyForUsersNames(
        ?int $accountId = null,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        ?int $auctionId = null
    ): string {
        return implode('_', ['names', $accountId, $startDateSysIso, $endDateSysIso, $auctionId]);
    }

    /**
     * Remove cache
     * @param int|null $accountId optional account filter
     * @param string|null $startDateSysIso optional start date filter
     * @param string|null $endDateSysIso optional end date filter
     * @param int|null $auctionId optional auction filter
     * @return bool
     */
    public function removeCacheForUsersNames(
        ?int $accountId = null,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        ?int $auctionId = null
    ): bool {
        $cacheKey = $this->generateCacheKeyForUsersNames(
            $accountId,
            $startDateSysIso,
            $endDateSysIso,
            $auctionId
        );
        $cacheManager = $this->getFilesystemCacheManager()
            ->setExtension('txt')
            ->setNamespace('winning_bidder_for_invoicing');
        return $cacheManager->delete($cacheKey);
    }

    /**
     * Return a list of auctions with un-invoiced items
     * Optional filters available
     * We always try to use cache
     * @param int|null $accountId optional account filter
     * @param string|null $startDateSysIso optional start date filter
     * @param string|null $endDateSysIso optional end date filter
     * @param int|null $userId optional user filter
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadAuctionNames(
        ?int $accountId = null,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        ?int $userId = null,
        bool $isReadOnlyDb = false
    ): array {
        $cacheKey = implode('_', ['names', $accountId, $startDateSysIso, $endDateSysIso, $userId]);
        $cacheManager = $this->getFilesystemCacheManager()
            ->setExtension('txt')
            ->setNamespace('sold_items_auction_for_uninvoiced');
        $allUnInvoicedAuctions = $cacheManager->get($cacheKey);
        if (!$allUnInvoicedAuctions) {
            $allUnInvoicedAuctions = $this->loadAuctionNamesFromDb(
                $accountId,
                $startDateSysIso,
                $endDateSysIso,
                $userId,
                $isReadOnlyDb
            );
            $cacheManager->set($cacheKey, $allUnInvoicedAuctions, self::CACHED_PARAM_LIFE_TIME);
        }
        return $allUnInvoicedAuctions;
    }

    /**
     * @param int|null $accountId optional account filter
     * @param string|null $startDateSysIso optional start date filter
     * @param string|null $endDateSysIso optional end date filter
     * @param int|null $userId optional user filter
     * @return string
     */
    protected function generateCacheKeyForAuctionNames(
        ?int $accountId = null,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        ?int $userId = null
    ): string {
        return implode('_', ['names', $accountId, $startDateSysIso, $endDateSysIso, $userId]);
    }

    /**
     * Remove cache
     * @param int|null $accountId optional account filter
     * @param string|null $startDateSysIso optional start date filter
     * @param string|null $endDateSysIso optional end date filter
     * @param int|null $userId optional user filter
     * @return bool
     */
    public function removeCacheForAuctionNames(
        ?int $accountId = null,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        ?int $userId = null
    ): bool {
        $cacheKey = $this->generateCacheKeyForAuctionNames($accountId, $startDateSysIso, $endDateSysIso, $userId);
        $cacheManager = $this->getFilesystemCacheManager()
            ->setExtension('txt')
            ->setNamespace('sold_items_auction_for_uninvoiced');
        return $cacheManager->delete($cacheKey);
    }

    /**
     * Return array of auctions with un-invoiced items
     * @param int|null $accountId
     * @param string|null $startDateSysIso
     * @param string|null $endDateSysIso
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadAuctionNamesFromDb(
        ?int $accountId = null,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        ?int $userId = null,
        bool $isReadOnlyDb = false
    ): array {
        $where = $this->buildMutualConditions(
            $accountId,
            $startDateSysIso,
            $endDateSysIso,
            [],
            $userId
        );
        // @formatter:off
        $query =
            "SELECT " .
                "a.id, " .
                "a.name, " .
                "IF(a.auction_type='" . Constants\Auction::TIMED . "', a.end_date, a.start_closing_date) AS auction_date, " .
                "atz.location AS auction_date_tz " .
            "FROM lot_item AS li " .
            "LEFT JOIN account acc ON acc.id = li.account_id " .
            "LEFT JOIN auction a ON a.id = li.auction_id " .
            // "LEFT JOIN auction_lot_item ali ON ali.auction_id = li.auction_id AND ali.lot_item_id = li.id " .
            "LEFT JOIN timezone atz ON atz.id = a.timezone_id " .
            "LEFT JOIN user AS u ON u.id = li.winning_bidder_id " .
            "WHERE " . $where .
            "GROUP BY a.id " .
            "ORDER BY " .
                "IF(a.auction_type='" . Constants\Auction::TIMED . "', a.end_date, a.start_closing_date) DESC, " .
                "a.name";
        // @formatter:on
        $this->query($query, $isReadOnlyDb);
        $auctionNames = [];
        $dateHelper = $this->getDateHelper();
        while ($row = $this->fetchAssoc()) {
            $auctionDate = new DateTime((string)$row['auction_date']);
            $dateNoTimeFormatted = $dateHelper->formattedDateWithoutTime($auctionDate, $accountId);
            $auctionNames[(int)$row['id']] = $dateNoTimeFormatted . ' - ' . $row['name'];
        }
        return $auctionNames;
    }

    /**
     * Get the lot items where invoices need to be generated for
     * @param int $accountId account.id
     * @param int|null $winningUserId optional non-invoiced items for user.id
     * @param string|null $startDateSysIso optional date range start; format "yyyy-mm-dd hh:mm:ss"
     * @param string|null $endDateSysIso optional date range end; format "yyyy-mm-dd hh:mm:ss"
     * @param int[] $auctionIds optional auction.id or array of auction.id for non-invoiced items
     * @return LotItem[]
     */
    public function loadLotItems(
        int $accountId,
        ?int $winningUserId = null,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        array $auctionIds = [],
        bool $isReadOnlyDb = false
    ): array {
        $this->enableReadOnlyDb($isReadOnlyDb);
        $where = $this->buildMutualConditions(
            $accountId,
            $startDateSysIso,
            $endDateSysIso,
            $auctionIds,
            $winningUserId
        );
        // @formatter:off
        $query =
            "SELECT li.* "
            . " FROM lot_item AS li"
            . " LEFT JOIN account acc ON acc.id = li.account_id"
            . " LEFT JOIN auction a ON a.id = li.auction_id"
            . " LEFT JOIN user AS u ON u.id = li.winning_bidder_id"
            . " WHERE " . $where
            . " ORDER BY li.winning_bidder_id, a.currency, a.sale_group, li.auction_id";
        // @formatter:on
        $dbResult = $this->query($query, $isReadOnlyDb);
        $lotItems = LotItem::InstantiateDbResult($dbResult);
        return $lotItems;
    }

    /**
     * Load user ids of winning bidders ready for invoicing
     * @param int $accountId
     * @param string|null $startDateSysIso
     * @param string|null $endDateSysIso
     * @param int[] $auctionIds
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadWinningUserIds(
        int $accountId,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        array $auctionIds = [],
        bool $isReadOnlyDb = false
    ): array {
        $where = $this->buildMutualConditions(
            $accountId,
            $startDateSysIso,
            $endDateSysIso,
            $auctionIds
        );
        // @formatter:off
        $query =
            "SELECT li.winning_bidder_id"
            . " FROM lot_item AS li"
            . " LEFT JOIN account acc ON acc.id = li.account_id"
            . " LEFT JOIN auction a ON a.id = li.auction_id"
            . " LEFT JOIN user AS u ON u.id = li.winning_bidder_id"
            . " WHERE " . $where
            . " GROUP BY li.winning_bidder_id";
        // @formatter:on
        $this->query($query, $isReadOnlyDb);
        $rows = $this->fetchAllAssoc();
        $winningBidderUserIds = ArrayCast::arrayColumnInt($rows, 'winning_bidder_id');
        return $winningBidderUserIds;
    }

    /**
     * https://bidpath.atlassian.net/browse/SAM-5162
     * Experimental
     * @param int $accountId
     * @param int|null $winningUserId
     * @param string|null $startDateSysIso
     * @param string|null $endDateSysIso
     * @param array $auctionIds
     * @param bool $isReadOnlyDb
     * @return LotItem[]
     */
    public function loadLotItemsNew(
        int $accountId,
        ?int $winningUserId = null,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        array $auctionIds = [],
        bool $isReadOnlyDb = false
    ): array {
        $where = $this->buildMutualConditionsSimplified(
            $accountId,
            $startDateSysIso,
            $endDateSysIso,
            $auctionIds,
            $winningUserId
        );
        $query = <<<SQL
SELECT li.* 
FROM lot_item as li 
LEFT JOIN account acc ON acc.id = li.account_id 
LEFT JOIN auction a ON a.id = li.auction_id 
LEFT JOIN user AS u ON u.id = li.winning_bidder_id 
WHERE {$where}
ORDER BY li.winning_bidder_id, a.currency, a.sale_group, li.auction_id;
SQL;
        $dbResult = $this->query($query, $isReadOnlyDb);
        $lotItems = LotItem::InstantiateDbResult($dbResult);
        return $lotItems;
    }

    /**
     * https://bidpath.atlassian.net/browse/SAM-5162
     * Experimental method
     * @param int $accountId
     * @param string|null $startDateSysIso
     * @param string|null $endDateSysIso
     * @param int[] $auctionIds
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadWinningUserIdsNew(
        int $accountId,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        array $auctionIds = [],
        bool $isReadOnlyDb = false
    ): array {
        $where = $this->buildMutualConditionsSimplified(
            $accountId,
            $startDateSysIso,
            $endDateSysIso,
            $auctionIds
        );
        $invoiceStatus = implode(',', Constants\Invoice::$openInvoiceStatuses);
        $query = <<<SQL
SELECT li.winning_bidder_id 
FROM lot_item as li
    LEFT JOIN account acc ON acc.id = li.account_id
    LEFT JOIN auction a ON a.id = li.auction_id 
    LEFT JOIN user AS u ON u.id = li.winning_bidder_id
    LEFT JOIN invoice i ON i.id=li.invoice_id AND i.invoice_status_id IN ({$invoiceStatus})
WHERE {$where} 
GROUP BY li.winning_bidder_id;
SQL;
        $this->query($query, $isReadOnlyDb);
        $winningBidderUserIds = [];
        while ($row = $this->fetchAssoc()) {
            $winningBidderUserIds[] = $row['winning_bidder_id'];
        }
        return $winningBidderUserIds;
    }

    /**
     * https://bidpath.atlassian.net/browse/SAM-5162
     * Experimental method
     * @param int|null $accountId
     * @param string|null $startDateSysIso
     * @param string|null $endDateSysIso
     * @param array $auctionIds
     * @param int|null $userId
     * @return string
     */
    protected function buildMutualConditionsSimplified(
        ?int $accountId = null,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        array $auctionIds = [],
        ?int $userId = null
    ): string {
        $n = "\n";
        $conds = [
            // @formatter:off
            "IF(a.id IS NULL," .
                // we allow billing lot items without "Sale sold in" assigned auction
                "true," .
                // We will invoice lots from reverse auctions in further
                "(!a.`reverse` OR a.`reverse` IS NULL)" .
                // If "sold in sale" auction assigned (li.auction_id), it should have respective status
                "AND a.auction_status_id IN (" . implode(',', Constants\Auction::$availableAuctionStatuses) . "))",
            "acc.active",
            // we allow billing lot items without "Sale sold in" assigned auction
            // and we allow billing lot from another auction even if it is deleted in this auction
            "li.active",
            "li.hammer_price IS NOT NULL",
            "li.winning_bidder_id IS NOT NULL",
            "li.invoice_id IS NULL",
            "u.user_status_id = " . Constants\User::US_ACTIVE,
            // @formatter:on
        ];

        if ($auctionIds) {
            $auctionIdsEscaped = [];
            foreach ($auctionIds as $auctionId) {
                $auctionIdsEscaped[] = $this->escape($auctionId);
            }
            $auctionIdList = implode(',', $auctionIdsEscaped);
            $conds[] = "li.auction_id IN ({$auctionIdList})";
        }

        if ($accountId) {
            $conds[] = "li.account_id = " . $this->escape($accountId);
        }

        if ($startDateSysIso) {
            $startDateUtc = $this->getDateHelper()->convertSysToUtcByDateIso($startDateSysIso);
            $startDateUtcIso = $startDateUtc->format(Constants\Date::ISO);
            $conds[] = "li.date_sold >= " . $this->escape($startDateUtcIso);
        }

        if ($endDateSysIso) {
            $endDateUtc = $this->getDateHelper()->convertSysToUtcByDateIso($endDateSysIso);
            $endDateUtcIso = $endDateUtc->format(Constants\Date::ISO);
            $conds[] = "li.date_sold <= " . $this->escape($endDateUtcIso);
        }

        if ($userId) {
            $conds[] = "li.winning_bidder_id = " . $this->escape($userId);
        }

        $cond = implode($n . ' AND ', $conds) . " ";
        return $cond;
    }


    /**
     * Return mutual WHERE conditions for joined tables: account acc, auction a, lot_item li
     * These conditions define ready for invoicing lot items and related records
     * @param int|null $accountId
     * @param string|null $startDateSysIso - period start date in system tz
     * @param string|null $endDateSysIso - period end date in system tz
     * @param int[] $auctionIds
     * @param int|null $userId
     * @return string
     */
    protected function buildMutualConditions(
        ?int $accountId = null,
        ?string $startDateSysIso = null,
        ?string $endDateSysIso = null,
        array $auctionIds = [],
        ?int $userId = null
    ): string {
        $n = "\n";

        // @formatter:off
        $conds = [
            "IF(a.id IS NULL," .
                // we allow billing lot items without "Sale sold in" assigned auction
                "true," .
                // We will invoice lots from reverse auctions in further
                "(!a.`reverse` OR a.`reverse` IS NULL)" .
                    // If "sold in sale" auction assigned (li.auction_id), it should have respective status
                    "AND a.auction_status_id IN (" . implode(',', Constants\Auction::$availableAuctionStatuses) . "))",
            "acc.active",
            // we allow billing lot items without "Sale sold in" assigned auction
            // and we allow billing lot from another auction even if it is deleted in this auction
            // "IF(ali.id IS NULL," .
            //     "true," .
            //     "ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . "))",
            "li.active",
            "li.hammer_price IS NOT NULL",
            "li.winning_bidder_id IS NOT NULL",
            "(SELECT count(1) FROM invoice_item AS ii " .
                "INNER JOIN invoice AS i on ii.invoice_id = i.id " .
                "WHERE ii.lot_item_id = li.id " .
                    "AND ii.active = true " . $n .
                    "AND ((i.invoice_status_id IN (" . implode(',', Constants\Invoice::$openInvoiceStatuses) . ") " .
                            "AND ii.release = false) " . $n .
                        "OR (i.invoice_status_id = " . Constants\Invoice::IS_CANCELED . " " .
                            "AND ii.winning_bidder_id = li.winning_bidder_id " .
                            "AND ii.auction_id = li.auction_id " .
                            "AND ii.release = false)" .
                    ") " . $n .
                    "AND ii.hammer_price IS NOT NULL) = 0",
            "u.user_status_id = " . Constants\User::US_ACTIVE,
        ];
        // @formatter:on

        if ($auctionIds) {
            $auctionIdsEscaped = [];
            foreach ($auctionIds as $auctionId) {
                $auctionIdsEscaped[] = $this->escape($auctionId);
            }
            $auctionIdList = implode(',', $auctionIdsEscaped);
            $conds[] = "li.auction_id IN ({$auctionIdList})";
        }

        if ($accountId) {
            $conds[] = "li.account_id = " . $this->escape($accountId);
        }

        if ($startDateSysIso) {
            $startDateUtc = $this->getDateHelper()->convertSysToUtcByDateIso($startDateSysIso);
            $startDateUtcIso = $startDateUtc->format(Constants\Date::ISO);
            $conds[] = "li.date_sold >= " . $this->escape($startDateUtcIso);
        }

        if ($endDateSysIso) {
            $endDateUtc = $this->getDateHelper()->convertSysToUtcByDateIso($endDateSysIso);
            $endDateUtcIso = $endDateUtc->format(Constants\Date::ISO);
            $conds[] = "li.date_sold <= " . $this->escape($endDateUtcIso);
        }

        if ($userId) {
            $conds[] = "li.winning_bidder_id = " . $this->escape($userId);
        }

        $cond = implode($n . ' AND ', $conds) . " ";
        return $cond;
    }
}
