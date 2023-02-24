<?php
/**
 * We use raw direct mysql query without qcodo framework to avoid redundant memory load.
 * We don’t need to apply LotList\DataSourceMysql approach, because we pass already known ids and don’t need to perform search/filtering/ordering conditions.
 *
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal\Load;

use Sam\AuctionLot\Sync\Response\Concrete\PublicData\Dto\SyncAuctionLotDto;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Log\Support\SupportLoggerAwareTrait;
use Sam\Lot\Search\Query\Build\Helper\LotSearchQueryBuilderHelperCreateTrait;
use Sam\Storage\Database\SimpleMysqliDatabaseAwareTrait;

/**
 * Class PublicDataLoader
 * @package Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal\Load
 * @internal
 */
class PublicDataLoader extends CustomizableClass
{
    use LotSearchQueryBuilderHelperCreateTrait;
    use OptionalsTrait;
    use SimpleMysqliDatabaseAwareTrait;
    use SupportLoggerAwareTrait;

    public const OP_MAIN_ACCOUNT_ID = OptionalKeyConstants::KEY_MAIN_ACCOUNT_ID; // int

    /**
     * @var bool
     */
    protected bool $isProfilingEnabled = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isProfilingEnabled
     * @param array $optionals = [
     *      self::OP_MAIN_ACCOUNT_ID => (int)
     *  ]
     * @return static
     */
    public function construct(bool $isProfilingEnabled, array $optionals = []): static
    {
        $this->isProfilingEnabled = $isProfilingEnabled;
        $this->getSimpleMysqliDatabase()->construct($isProfilingEnabled);
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Load auction lots data for syncing frontend catalog page
     *
     * @param int|null $editorUserId
     * @param array $auctionLotIds
     * @param string $displayBidderInfo
     * @return SyncAuctionLotDto[]
     */
    public function loadAuctionLotDtos(?int $editorUserId, array $auctionLotIds, string $displayBidderInfo): array
    {
        $query = $this->makeAuctionLotsQuery($editorUserId, $auctionLotIds, $displayBidderInfo);
        if ($this->isProfilingEnabled) {
            $this->getSupportLogger()->debug($query);
        }
        $tmpTs = microtime(true);

        $rows = $this->getSimpleMysqliDatabase()
            ->query($query)
            ->fetch_all(MYSQLI_ASSOC);

        if ($this->isProfilingEnabled) {
            $this->getSupportLogger()->debug('main query: ' . ((microtime(true) - $tmpTs) * 1000) . 'ms');
        }
        $auctionLots = array_map(
            static function (array $row) {
                return SyncAuctionLotDto::new()->fromDbRow($row);
            },
            $rows
        );
        return $auctionLots;
    }

    /**
     * Load auction lot changes history
     *
     * @param array $auctionLotIds
     * @return array $auctionLotChanges = [
     *      (auctionLotId) => [
     *          (userId) => (timestamp)
     *      ]
     *  ]
     */
    public function loadAuctionLotChanges(array $auctionLotIds): array
    {
        $query = $this->makeAuctionLotChangesQuery($auctionLotIds);
        $dbResult = $this->getSimpleMysqliDatabase()->query($query);
        $auctionLotChanges = [];
        while ($row = $dbResult->fetch_assoc()) {
            $auctionLotId = (int)$row['ali_id'];
            $userId = (int)$row['user_id'];
            $auctionLotChanges[$auctionLotId][$userId] = strtotime($row['created_on']);
        }
        return $auctionLotChanges;
    }

    /**
     * @param int|null $editorUserId
     * @param array $auctionLotIds
     * @param string $displayBidderInfo
     * @return string
     */
    protected function makeAuctionLotsQuery(?int $editorUserId, array $auctionLotIds, string $displayBidderInfo): string
    {
        $auctionLotIdList = $this->prepareAuctionLotIdListExpression($auctionLotIds);
        $userIdEscaped = $this->getSimpleMysqliDatabase()->escape($editorUserId);
        $queryBuilderHelper = $this->createLotSearchQueryBuilderHelper();
        $userQuery = 'NULL as max_bid,';
        $nowEscaped = $this->getSimpleMysqliDatabase()->escape(gmdate(Constants\Date::ISO));
        $mainAccountId = $this->fetchOptional(self::OP_MAIN_ACCOUNT_ID);

        // @formatter:off
        if ($editorUserId) {
            $userQuery =
                "IF(a.auction_type = '" . Constants\Auction::TIMED . "', " .
                    "(SELECT bt.max_bid FROM bid_transaction bt " .
                        "WHERE bt.user_id = {$userIdEscaped} " .
                            "AND bt.lot_item_id = ali.lot_item_id " .
                            "AND bt.auction_id = ali.auction_id " .
                            "AND NOT bt.deleted " .
                            "AND NOT bt.failed " .
                        "ORDER BY bt.id DESC " .
                        "LIMIT 1" .
                    "), " .
                    "(SELECT ab.max_bid FROM absentee_bid ab " .
                        "WHERE ab.lot_item_id = ali.lot_item_id " .
                            "AND ab.auction_id = ali.auction_id " .
                            "AND ab.user_id = '{$userIdEscaped}'" .
                    ")" .
                ") AS max_bid,";
        }

        $dbiNumber = Constants\SettingAuction::DBI_NUMBER;
        $dbiUsername = Constants\SettingAuction::DBI_USERNAME;
        $dbiCompanyName = Constants\SettingAuction::DBI_COMPANY_NAME;
        $query =
            "SELECT " .
                "li.id AS id, " .
                "li.reserve_price AS reserve_price, " .
                "li.hammer_price AS hammer_price, " .
                "li.changes AS changes, " .
                "li.changes_timestamp AS changes_timestamp, " .
                "li.winning_bidder_id AS winning_bidder_id, " .
                "li.consignor_id AS consignor_id, " .
                "IF('$displayBidderInfo' = '{$dbiNumber}' AND li.winning_bidder_id IS NOT NULL, " .
                    "IFNULL(" .
                        "(SELECT IF(bidder_num IS NOT NULL AND bidder_num != '', bidder_num, NULL) FROM auction_bidder WHERE auction_id = a.id AND user_id = li.winning_bidder_id), " .
                        "'floor bidder'" .
                    "), " .
                    "IF('$displayBidderInfo' = '{$dbiUsername}', " .
                        "IFNULL(" .
                            "(SELECT IF(username IS NOT NULL AND username != '', username, NULL) FROM `user` WHERE id = li.winning_bidder_id), " .
                            "'floor bidder'" .
                        "), " .
                        "IF('$displayBidderInfo' = '{$dbiCompanyName}', " .
                            "IFNULL(" .
                                "(SELECT " .
                                    "IF(company_name IS NOT NULL AND company_name != '', " .
                                        "company_name, " .
                                        "IF((SELECT IF(username IS NOT NULL AND username != '', username, NULL) FROM `user` WHERE id = li.winning_bidder_id) IS NOT NULL, " .
                                            "(SELECT IF(username IS NOT NULL AND username != '', username, NULL) FROM `user` WHERE id = li.winning_bidder_id), " .
                                            "'floor bidder'" .
                                        ")" .
                                    ")" .
                                " FROM user_info WHERE user_id = li.winning_bidder_id), " .
                                "(SELECT " .
                                    "IF(username IS NOT NULL AND username != '', username, NULL)" .
                                " FROM `user` WHERE id = li.winning_bidder_id)" .
                            "), " .
                            "'floor bidder'" .
                        ") " .
                    ")" .
                ") AS wbinfo, " .

                "ali.id AS alid, " .
                "ali.lot_status_id AS lot_status_id, " .
                "ali.quantity AS qty, " .
                "COALESCE(
                    ali.quantity_digits, 
                    li.quantity_digits, 
                    (SELECT lc.quantity_digits
                     FROM lot_category lc
                       INNER JOIN lot_item_category lic ON lc.id = lic.lot_category_id
                     WHERE lic.lot_item_id = li.id
                       AND lc.active = 1
                     ORDER BY lic.id
                     LIMIT 1), 
                    seta.quantity_digits
                ) as qty_scale, " .
                "IF (ali.quantity_x_money = true,1,0) AS qty_x_money, " .
                "ali.buy_now_amount AS buy_amount, " .
                "alic.starting_bid_normalized AS starting_bid_normalized, " .
                "alic.current_bid AS current_bid, " .
                $userQuery .
                "alic.current_bidder_id AS current_bidder_id, " .
                "alic.current_max_bid AS current_max_bid, " .
                "alic.asking_bid AS asking_bid, " .
                "alic.bid_count AS bid_count, " .
                "alic.end_date AS end_date, ".
                "alic.bulk_master_asking_bid AS bulk_master_asking_bid, " .
                "UNIX_TIMESTAMP(" . $queryBuilderHelper->getLotStartDateExpr() . ")" .
                    " - UNIX_TIMESTAMP({$nowEscaped}) AS seconds_before, " .
                "UNIX_TIMESTAMP(" . $queryBuilderHelper->getLotEndDateExpr() . ")" .
                    " - UNIX_TIMESTAMP({$nowEscaped}) AS seconds_left, " .
                $queryBuilderHelper->getLotStartDateExpr() . ' AS lot_st_dt, ' .
                $queryBuilderHelper->getLotEndDateExpr() . ' AS lot_en_dt, ' .
                "IF (toi.no_bidding,1,0) AS no_bidding, " .
                "a.id AS auction_id, " .
                "a.next_bid_button AS next_bid_button, ".
                "a.auction_type AS auction_type, " .
                "a.auction_status_id AS auction_status_id, " .
                "a.account_id AS account_id, " .
                "a.lot_winning_bid_access AS lot_winning_bid_access, " .
                "a.notify_absentee_bidders, " .
                "IF(a.reverse,1,0) AS auction_reverse, " .
                "IF(a.currency IS NOT NULL AND curr.sign <> '', " .
                    "curr.sign, " .
                    "(SELECT curr.sign FROM currency curr" .
                        " LEFT JOIN setting_system setsys ON setsys.primary_currency_id = curr.id" .
                        " WHERE setsys.account_id = " . $this->getSimpleMysqliDatabase()->escape($mainAccountId) . ")" .
                ") AS str_currency, " .
                "IF(a.currency IS NOT NULL, a.currency, 0) AS auction_currency_id ," .
                "a.absentee_bids_display AS absentee_bids_display, " .
                "a.listing_only AS auction_listing_only, " .
                "a.bidding_paused AS bidding_paused, " .
                "a.reserve_not_met_notice AS reserve_not_met_notice, " .
                "a.reserve_met_notice AS reserve_met_notice, " .
                // "a.auction_visibility_access AS auction_visibility_access, " .
                // "a.auction_info_access AS auction_info_access, " .
                // "a.auction_catalog_access AS auction_catalog_access, " .
                // "a.live_view_access AS live_view_access, " .
                // "a.lot_details_access AS lot_details_access, " .
                // "a.lot_bidding_history_access AS lot_bidding_history_access, " .
                // "a.lot_starting_bid_access AS lot_starting_bid_access, " .
                "a.lot_bidding_info_access AS lot_bidding_info_access, " .
                "ali.listing_only AS listing_only, " .
                "rtbc.lot_item_id AS rtb_current_lot_id, " .
                "bt.bid AS current_transaction_bid, " .
                "setrtb.buy_now_restriction AS ap_buy_now_restriction, " .
                "seta.buy_now_unsold AS ap_buy_now_unsold, " .
                "u.flag AS user_flag, " .
                "ua.flag AS user_account_flag, " .
                "aub.bidder_num AS bidder_num, " .
                "rtbc.lot_end_date AS rtb_lot_end_date, " .
                "rtbc.pause_date AS rtb_pause_date, " .
                "rtbc.lot_active AS rtb_lot_active, " .
                "ali.`order` AS order_num, " .
                "a.extend_time AS extend_time, " .
                "a.lot_start_gap_time AS lot_start_gap_time, " .
                "a.start_closing_date AS auction_start_closing_date, " .
                "atz.location AS auc_tz_location " .
            "FROM auction_lot_item AS ali " .
            "INNER JOIN lot_item AS li ON li.id = ali.lot_item_id " .
            "INNER JOIN auction AS a ON a.id = ali.auction_id " .
            "LEFT JOIN auction_dynamic AS adyn ON adyn.auction_id = ali.auction_id " .
            "LEFT JOIN timed_online_item AS toi ON toi.auction_id = ali.auction_id AND toi.lot_item_id = ali.lot_item_id " .
            "LEFT JOIN timezone AS tz ON tz.id = ali.timezone_id " .
            "LEFT JOIN timezone AS atz ON atz.id = a.timezone_id " .
            "LEFT JOIN currency AS curr ON curr.id = a.currency " .
            "LEFT JOIN auction_lot_item_cache alic ON alic.auction_lot_item_id = ali.id " .
            "LEFT JOIN rtb_current rtbc ON rtbc.auction_id = ali.auction_id " .
            "LEFT JOIN bid_transaction bt ON bt.id = ali.current_bid_id AND !failed AND !deleted " .
            "LEFT JOIN setting_auction seta ON seta.account_id = li.account_id " .
            "LEFT JOIN setting_rtb setrtb ON setrtb.account_id = li.account_id " .
            "LEFT JOIN `user` u ON u.id = '{$userIdEscaped}' " .
            "LEFT JOIN user_account ua ON ua.user_id = u.id AND ua.account_id = li.account_id " .
            "LEFT JOIN auction_bidder aub ON aub.user_id = u.id AND aub.auction_id = ali.auction_id " .
            "WHERE " .
                "ali.id IN (" . $auctionLotIdList . ") " .
                "AND li.active = true " .
                "AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ")";
        // @formatter:on
        return $query;
    }

    /**
     * @param array $auctionLotIds
     * @return string
     */
    protected function makeAuctionLotChangesQuery(array $auctionLotIds): string
    {
        $auctionLotIdList = $this->prepareAuctionLotIdListExpression($auctionLotIds);
        // @formatter:off
        $query =
            'SELECT'
            . ' alich.*,'
            . ' ali.`id` AS ali_id'
            . ' FROM auction_lot_item_changes AS alich'
            . ' LEFT JOIN auction_lot_item AS ali'
            . ' ON ali.lot_item_id = alich.lot_item_id'
            . ' AND ali.auction_id = alich.auction_id'
            . ' WHERE ali.id IN (' . $auctionLotIdList . ')';
        // @formatter:on
        return $query;
    }

    /**
     * Escape parameters passed in url query
     *
     * @param array $auctionLotIds
     * @return string
     */
    protected function prepareAuctionLotIdListExpression(array $auctionLotIds): string
    {
        $auctionLotIdsEscaped = array_map(
            function ($id) {
                return $this->getSimpleMysqliDatabase()->escape($id);
            },
            $auctionLotIds
        );
        $auctionLotIdList = implode(',', $auctionLotIdsEscaped);
        return $auctionLotIdList;
    }

    /**
     * @param int $auctionId
     * @return array
     */
    public function loadAuctionAdditionalCurrencies(int $auctionId): array
    {
        $currencyIds = [];
        $query = 'SELECT currency_id FROM auction_currency WHERE auction_id = ' . $this->getSimpleMysqliDatabase()->escape($auctionId);
        $result = $this->getSimpleMysqliDatabase()->query($query);
        while ($row = $result->fetch_array()) {
            $currencyIds[] = (int)$row['currency_id'];
        }
        return $currencyIds;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_MAIN_ACCOUNT_ID] = $optionals[self::OP_MAIN_ACCOUNT_ID]
            ?? static function (): int {
                return (int)ConfigRepository::getInstance()->get('core->portal->mainAccountId');
            };
        $this->setOptionals($optionals);
    }
}
