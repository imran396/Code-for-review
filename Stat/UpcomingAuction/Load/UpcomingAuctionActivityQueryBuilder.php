<?php
/**
 * SAM-7949: Predictive upcoming auction stats script
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Stat\UpcomingAuction\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Lot\Search\Query\Build\Helper\LotSearchQueryBuilderHelperCreateTrait;

/**
 * Class UpcomingAuctionActivityQueryBuilder
 * @package Sam\Stat\UpcomingAuction\Load
 */
class UpcomingAuctionActivityQueryBuilder extends CustomizableClass
{
    use LotSearchQueryBuilderHelperCreateTrait;
    use OptionalsTrait;

    public const INTERVAL_HOUR = 3600;

    public const OP_MAIN_ACCOUNT_ID = OptionalKeyConstants::KEY_MAIN_ACCOUNT_ID; // int
    public const OP_APP_HTTP_HOST = OptionalKeyConstants::KEY_APP_HTTP_HOST; // string
    public const OP_PORTAL_URL_HANDLING = OptionalKeyConstants::KEY_PORTAL_URL_HANDLING; // string

    protected const LIVE_AUCTION_LOTS_PER_HOUR_PERCENTILE = 0.85;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param int $days
     * @param bool $addComments
     * @param int $interval
     * @return string
     */
    public function buildQuery(int $days, bool $addComments = false, int $interval = self::INTERVAL_HOUR): string
    {
        $query = '';
        if ($addComments) {
            $query .= $this->makeComment('Active Auctions');
        }
        $query .= $this->makeCollectActiveAuctionsQuery();
        if ($addComments) {
            $query .= $this->makeComment('Activity table');
        }
        $query .= $this->makeCreateAuctionActivityTemporaryTableQuery();
        if ($addComments) {
            $query .= $this->makeComment('Timed auctions');
        }
        $query .= $this->makeCollectTimedAuctionActivityQuery($interval, $days);
        if ($addComments) {
            $query .= $this->makeComment('Hybrid auctions');
        }
        $query .= $this->makeCollectHybridAuctionActivityQuery($interval, $days);
        if ($addComments) {
            $query .= $this->makeComment('Live auctions');
        }
        $query .= $this->makeCollectLiveAuctionActivityQuery($interval, $days);
        if ($addComments) {
            $query .= $this->makeComment('Result');
        }
        $query .= $this->makeSelectCollectedActivityQuery();
        return $query;
    }

    /**
     * @return string
     */
    protected function makeCollectActiveAuctionsQuery(): string
    {
        $mainAccountId = $this->fetchOptional(self::OP_MAIN_ACCOUNT_ID);
        $httpHost = $this->fetchOptional(self::OP_APP_HTTP_HOST);
        $portalUrlHandling = $this->fetchOptional(self::OP_PORTAL_URL_HANDLING);
        $openAuctionStatuses = implode(',', Constants\Auction::$openAuctionStatuses);
        $availableLotStatuses = implode(',', Constants\Lot::$availableLotStatuses);

        $query = <<<SQL
DROP TEMPORARY TABLE IF EXISTS active_auctions;
CREATE TEMPORARY TABLE active_auctions (PRIMARY KEY (auction_id), INDEX (auction_type), INDEX (account_id)) ENGINE=InnoDB
SELECT
  a.id AS auction_id,
  a.account_id,
  a.auction_type,
  IF(a.publish_date AND (a.unpublish_date IS NULL OR a.unpublish_date > UTC_TIMESTAMP()), 1, 0) AS published,
  COUNT(DISTINCT ab.user_id) AS bidders_registered,
  (SELECT COUNT(*) FROM auction_lot_item ali WHERE ali.auction_id=a.id AND ali.lot_status_id IN ({$availableLotStatuses})) AS lots,
  IF(a.account_id = {$mainAccountId}, '${httpHost}',
     IF('{$portalUrlHandling}' = 'maindomain' AND acc.url_domain <> '', acc.url_domain, CONCAT(acc.name, '.{$httpHost}'))
    ) AS `domain`
FROM auction a
       INNER JOIN account acc ON acc.id=a.account_id AND acc.active
       LEFT JOIN auction_bidder ab ON ab.auction_id=a.id
WHERE a.auction_status_id IN ({$openAuctionStatuses})
  AND (a.listing_only IS NULL OR a.listing_only=0)
  AND (a.test_auction IS NULL OR a.test_auction=0)
GROUP BY a.id;
SQL;
        return $query;
    }

    /**
     * @return string
     */
    protected function makeSelectCollectedActivityQuery(): string
    {
        $query = <<<SQL
SELECT
  utc,
  aa.lots_active,
  aa.bids_placed,
  aa.bidders_bidding,
  act.bidders_registered,
  act.domain,
  act.auction_type,
  aa.auction_id,
  act.published,
  a.name
FROM auction_activity aa
       LEFT JOIN active_auctions act ON act.auction_id=aa.auction_id
       LEFT JOIN account acc ON acc.id=act.account_id
       LEFT JOIN auction a ON a.id=aa.auction_id
GROUP BY utc, aa.auction_id;
SQL;
        return $query;
    }

    /**
     * @return string
     */
    protected function makeCreateAuctionActivityTemporaryTableQuery(): string
    {
        $query = <<<SQL
DROP TEMPORARY TABLE IF EXISTS auction_activity;
CREATE TEMPORARY TABLE auction_activity (
                          utc DATETIME,
                          auction_id INT(10),
                          bids_placed INT(10),
                          bidders_bidding INT(10),
                          lots_active INT(10),
                          PRIMARY KEY (utc,auction_id),
                          INDEX idx_aa_auction_id (auction_id)
) ENGINE=InnoDB;
SQL;
        return $query;
    }

    /**
     * @param int $interval
     * @param int $days
     * @return string
     */
    public function makeCollectTimedAuctionActivityQuery(int $interval, int $days): string
    {
        $lsActive = Constants\Lot::LS_ACTIVE;
        $timed = Constants\Auction::TIMED;
        $lotEndDateExpr = $this->createLotSearchQueryBuilderHelper()->getTimedLotEndDateExpr();
        $sql = <<<SQL
INSERT INTO auction_activity (auction_id, bids_placed, bidders_bidding, lots_active, utc)
SELECT
  aa.auction_id,
  COUNT(bt.id) AS bids_placed,
  COUNT(DISTINCT bt.user_id) AS bidders_bidding,
  COUNT(DISTINCT ali.id) AS lots,
  FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP({$lotEndDateExpr})/{$interval})*{$interval}) AS UTC
FROM active_auctions aa
       INNER JOIN auction a ON a.id=aa.auction_id
       LEFT JOIN auction_lot_item ali ON ali.auction_id=aa.auction_id AND ali.lot_status_id={$lsActive} AND (ali.listing_only IS NULL OR ali.listing_only=0)
       LEFT JOIN auction_lot_item_cache alic ON alic.auction_lot_item_id=ali.id
       LEFT JOIN bid_transaction bt ON bt.auction_id=a.id AND bt.lot_item_id=ali.lot_item_id
       LEFT JOIN auction_dynamic adyn ON a.id = adyn.auction_id
WHERE aa.auction_type='{$timed}'
  AND {$lotEndDateExpr} BETWEEN DATE_SUB(UTC_TIMESTAMP(), INTERVAL 1 DAY) AND DATE_ADD(UTC_TIMESTAMP(), INTERVAL {$days} DAY)
GROUP BY FLOOR(UNIX_TIMESTAMP({$lotEndDateExpr})/{$interval}), aa.auction_id;
SQL;
        return $sql;
    }

    /**
     * @param int $interval
     * @param int $days
     * @return string
     */
    public function makeCollectHybridAuctionActivityQuery(int $interval, int $days): string
    {
        $lsActive = Constants\Lot::LS_ACTIVE;
        $hybrid = Constants\Auction::HYBRID;
        $sql = <<<SQL
INSERT INTO auction_activity (auction_id, bids_placed, bidders_bidding, lots_active, utc)
SELECT
  aa.auction_id,
  COUNT(ab.id) AS bids_placed,
  COUNT(DISTINCT ab.user_id) AS bidders_bidding,
  COUNT(DISTINCT ali.id) AS lots,
  FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(ali.start_closing_date)/{$interval})*{$interval}) AS UTC
FROM active_auctions aa
       LEFT JOIN auction a ON a.id=aa.auction_id
       LEFT JOIN auction_lot_item ali ON ali.auction_id=aa.auction_id AND ali.lot_status_id={$lsActive} AND (ali.listing_only IS NULL or ali.listing_only=0)
       LEFT JOIN absentee_bid ab on ab.auction_id=aa.auction_id AND ab.lot_item_id=ali.lot_item_id
WHERE aa.auction_type='{$hybrid}'
  AND ali.start_closing_date BETWEEN DATE_SUB(UTC_TIMESTAMP(), INTERVAL 1 DAY) AND DATE_ADD(UTC_TIMESTAMP(), INTERVAL {$days} DAY)
GROUP BY FLOOR(UNIX_TIMESTAMP(ali.start_closing_date)/{$interval}), aa.auction_id;
SQL;
        return $sql;
    }

    /**
     * @param int $interval
     * @param int $days
     * @return string
     */
    public function makeCollectLiveAuctionActivityQuery(int $interval, int $days): string
    {
        $availableLotStatuses = implode(',', Constants\Lot::$availableLotStatuses);
        $live = Constants\Auction::LIVE;

        $sql = $this->makeLotsPerIntervalPerAccountForLiveAuctionQuery($interval);
        $sql .= <<<SQL
INSERT INTO auction_activity (auction_id, bids_placed, bidders_bidding, lots_active, utc)
SELECT
  aa.auction_id,
  COUNT(ab.id) AS bids_placed,
  COUNT(DISTINCT ab.user_id) AS bidders_bidding,
  COUNT(DISTINCT ali.id) AS lots,
  FROM_UNIXTIME(
      FLOOR(
          UNIX_TIMESTAMP(
            DATE_ADD(
              IFNULL(a.start_date, a.start_closing_date),
              INTERVAL 24*60*FLOOR(ali.order/(lpi.lpi*8*({$interval}/3600))) + ((CAST(ali.order AS signed)-1-FLOOR(ali.order/(lpi.lpi*8*({$interval}/3600)))*(lpi.lpi*8*({$interval}/3600))) * ((60*({$interval}/3600))/lpi.lpi)) MINUTE
              )
            )/{$interval}
        )*{$interval}
    ) utc
FROM active_auctions aa
       LEFT JOIN auction a ON a.id=aa.auction_id
       LEFT JOIN lots_per_interval_per_account lpi ON lpi.account_id=aa.account_id
       INNER JOIN auction_lot_item ali ON ali.auction_id=aa.auction_id AND ali.lot_status_id IN ({$availableLotStatuses}) AND (ali.listing_only IS NULL OR ali.listing_only=0)
       LEFT JOIN absentee_bid ab on ab.auction_id=aa.auction_id AND ab.lot_item_id=ali.lot_item_id
WHERE aa.auction_type='{$live}'
  AND DATE_ADD(
  IFNULL(a.start_date, a.start_closing_date),
  INTERVAL 24*60*FLOOR(ali.order/(lpi.lpi*8*({$interval}/3600))) + ((CAST(ali.order AS signed)-1) * ((60*({$interval}/3600))/lpi.lpi)) MINUTE
  ) BETWEEN DATE_SUB(UTC_TIMESTAMP(), INTERVAL 1 DAY) AND DATE_ADD(UTC_TIMESTAMP(), INTERVAL {$days} DAY)
GROUP BY FLOOR(
             UNIX_TIMESTAMP(
               DATE_ADD(
                 IFNULL(a.start_date, a.start_closing_date),
                 INTERVAL 24*60*FLOOR(ali.order/(lpi.lpi*8*({$interval}/3600))) + ((CAST(ali.order AS signed)-1) * ((60*({$interval}/3600))/lpi.lpi)) MINUTE
                 )
               )/{$interval}
           ), aa.auction_id;
SQL;
        return $sql;
    }

    /**
     * @param int $interval
     * @return string
     */
    protected function makeLotsPerIntervalPerAccountForLiveAuctionQuery(int $interval): string
    {
        $percentile = self::LIVE_AUCTION_LOTS_PER_HOUR_PERCENTILE;
        $live = Constants\Auction::LIVE;
        $finishedAuctionStatus = implode(',', [Constants\Auction::AS_CLOSED, Constants\Auction::AS_ARCHIVED]);
        $sql = <<<SQL
SET @Interval := {$interval};
SET @Percentile := {$percentile};

DROP TEMPORARY TABLE IF EXISTS lots_per_day;
CREATE TEMPORARY TABLE lots_per_day (INDEX idx_lpd_auction_id (auction_id)) ENGINE=InnoDB
SELECT
  a.id AS auction_id,
  ac.total_lots,
   COUNT(DISTINCT bt.lot_item_id) AS lots_per_day,
  DATE_FORMAT(DATE_ADD(CONVERT_TZ(bt.created_on, 'UTC', tz.location), INTERVAL -5 HOUR), '%Y-%m-%d') `date`
FROM auction a
      LEFT JOIN auction_cache ac ON ac.auction_id=a.id
      INNER JOIN bid_transaction bt on bt.auction_id=a.id AND bt.created_on BETWEEN a.start_closing_date AND a.end_date
      LEFT JOIN timezone tz ON tz.id=a.timezone_id
WHERE a.auction_status_id IN ({$finishedAuctionStatus})
  AND a.auction_type='{$live}'
  AND a.start_closing_date > DATE_SUB(UTC_TIMESTAMP(), INTERVAL 3 YEAR)
GROUP BY a.id, DATE_FORMAT(DATE_ADD(CONVERT_TZ(bt.created_on, 'UTC', tz.location), INTERVAL -5 HOUR), '%Y-%m-%d')
HAVING lots_per_day > 0;

DROP TEMPORARY TABLE IF EXISTS hours_per_day;
CREATE TEMPORARY TABLE hours_per_day (INDEX idx_hpd_auction_id (auction_id)) ENGINE=InnoDB
SELECT
  a.id AS auction_id,
  DATE_FORMAT(DATE_ADD(CONVERT_TZ(bt.created_on, 'UTC', tz.location), INTERVAL -5 HOUR), '%Y-%m-%d') AS `date`,
  CEIL((UNIX_TIMESTAMP(MAX(bt.created_on))-UNIX_TIMESTAMP(MIN(bt.created_on)) + 1)/60/60) AS hours
FROM auction a
       LEFT JOIN timezone tz ON tz.id=a.timezone_id
       INNER JOIN bid_transaction bt on bt.auction_id=a.id AND bt.created_on BETWEEN a.start_closing_date AND a.end_date
WHERE a.auction_status_id IN ({$finishedAuctionStatus})
  AND a.auction_type='{$live}'
  AND a.start_date > DATE_SUB(UTC_TIMESTAMP(), INTERVAL 3 YEAR)
GROUP BY a.id, DATE_FORMAT(DATE_ADD(CONVERT_TZ(bt.created_on, 'UTC', tz.location), INTERVAL -5 HOUR), '%Y-%m-%d');

DROP TEMPORARY TABLE IF EXISTS lots_per_interval;
CREATE TEMPORARY TABLE lots_per_interval (INDEX idx_lph_account_id_lph (account_id, lots_per_interval)) ENGINE=InnoDB
SELECT
  lpd.auction_id,
  a.account_id,
  hpd.date,
  lpd.lots_per_day,
  hpd.hours,
  IFNULL(CEIL(lpd.lots_per_day/hpd.hours * (@Interval / 3600)), 0) AS lots_per_interval
FROM lots_per_day lpd
       LEFT JOIN auction a ON a.id = lpd.auction_id
       INNER JOIN hours_per_day hpd ON hpd.auction_id = lpd.auction_id AND hpd.date = lpd.date;
       
# Place one default lots per interval per account
INSERT INTO lots_per_interval (account_id, lots_per_interval)
SELECT id, ROUND(80 * (@Interval / 3600))
FROM account
WHERE active;

# Add a second one so the algorithm works default lots per interval per account
INSERT INTO lots_per_interval (account_id, lots_per_interval)
SELECT id, ROUND(70 * (@Interval / 3600))
FROM account
WHERE active;

DROP TEMPORARY TABLE IF EXISTS lpi_per_account;
CREATE TEMPORARY TABLE lpi_per_account (INDEX idx_plipa_account_id (account_id)) ENGINE=InnoDB
SELECT account_id, COUNT(*) entries
FROM lots_per_interval
GROUP BY account_id;

DROP TEMPORARY TABLE IF EXISTS lpi_1;
CREATE TEMPORARY TABLE lpi_1 (INDEX idx_lpi_1_account_id (account_id)) ENGINE=InnoDB
SELECT count(*) r, lots_per_interval, account_id FROM lots_per_interval GROUP BY account_id, lots_per_interval;

DROP TEMPORARY TABLE IF EXISTS lpi_2;
CREATE TEMPORARY TABLE lpi_2 LIKE lpi_1;
INSERT INTO lpi_2 SELECT * FROM lpi_1;

DROP TEMPORARY TABLE IF EXISTS lots_per_interval_per_account;
CREATE TEMPORARY TABLE lots_per_interval_per_account (INDEX idx_lph_account_id (account_id)) ENGINE=InnoDB
SELECT
  account_id, sr, l AS lpi, p AS percentile
FROM (
       SELECT
         g2.account_id,
         SUM(g1.r) AS sr,
         g2.lots_per_interval AS l,
         SUM(g1.r)/(SELECT lpipa.entries FROM lpi_per_account lpipa WHERE lpipa.account_id=g2.account_id) AS p
       FROM lpi_1 g1
              LEFT JOIN lpi_2 g2 ON g1.account_id=g2.account_id AND g1.lots_per_interval < g2.lots_per_interval
       GROUP BY g2.account_id, g2.lots_per_interval
       HAVING p <= @Percentile
       ORDER BY account_id, p DESC
     ) x
GROUP BY x.account_id;
SQL;
        return $sql;
    }

    /**
     * @param string $commentText
     * @return string
     */
    protected function makeComment(string $commentText): string
    {
        $comment = <<<TEXT


#####
# {$commentText}
#####

TEXT;
        return $comment;
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
        $optionals[self::OP_APP_HTTP_HOST] = $optionals[self::OP_APP_HTTP_HOST]
            ?? static function (): string {
                return (string)ConfigRepository::getInstance()->get('core->app->httpHost');
            };
        $optionals[self::OP_PORTAL_URL_HANDLING] = $optionals[self::OP_PORTAL_URL_HANDLING]
            ?? static function (): string {
                return (string)ConfigRepository::getInstance()->get('core->portal->urlHandling');
            };
        $this->setOptionals($optionals);
    }
}
