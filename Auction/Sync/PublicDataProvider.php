<?php
/**
 * Class for defining ajax sync status constant.
 *
 * Related tickets:
 * SAM-2978: Improve auction lot data sync scripts
 *
 * @copyright  2018 Bidpath, Inc.
 * @author     Imran Rahman
 * @package    com.swb.sam2
 * @version    $Id$
 * @since      jan 21, 2016
 * @copyright  Copyright 2018 by Bidpath, Inc. All rights reserved.
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Auction\Sync;

use DateTime;
use DateTimeZone;
use Exception;
use Sam\Account\Validate\AccountExistenceCheckerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Date\DateHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Sync\DataProviderBase;

/**
 * Class PublicDataProvider
 * @package Sam\Auction\Sync
 */
class PublicDataProvider extends DataProviderBase
{
    use AccountExistenceCheckerAwareTrait;
    use ConfigRepositoryAwareTrait;
    use DateHelperAwareTrait;

    protected array $auctionIds = [];
    protected ?int $systemAccountId = null;
    protected bool $isStartEnding = false;    // Return auction start ending date
    protected bool $isEndDate = false;
    protected string $tz = '';
    protected bool $isUpdateList = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        parent::initInstance();
        $this->cacheNamespace = 'sync-auctions';
        $this->ttl = $this->cfg()->get('core->auction->list->syncCacheTtl');
        return $this;
    }

    /**
     * Run the thing
     * @return void
     */
    public function run(): void
    {
        $this->process();
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function process(): void
    {
        $fileCacheManager = $this->getCacheManager()
            ->setNamespace('sync-auctions')
            ->setExtension('json');
        $cacheData = $fileCacheManager->has($this->getCacheKey())
            ? $fileCacheManager->get($this->getCacheKey()) : null;
        if ($cacheData) {
            echo $cacheData;
            return;
        }

        $this->auctionData = [];

        if (count($this->auctionIds) <= 0) {
            $this->render();
            return;
        }

        if ($this->systemAccountId) {
            if (!$this->getAccountExistenceChecker()->existById($this->systemAccountId, true)) {
                log_error("Account with id '{$this->systemAccountId}' does not exist");
                $this->render();
                return;
            }
        } else {
            $this->systemAccountId = cfg()->core->portal->mainAccountId;
        }

        $query = $this->getAuctionsQuery();

        $tmpTS = microtime(true);
        $dbResult = $this->db->query($query);
        if ($this->isProfiling()) {
            error_log('main query: ' . ((microtime(true) - $tmpTS) * 1000) . 'ms');
        }

        $auctionStatusPureChecker = AuctionStatusPureChecker::new();

        $tmpTS = microtime(true);
        while ($arr = $dbResult->fetch_array()) {
            $data = [];

            $data = $this->onPreIteration($arr, $data);

            $auctionId = (int)$arr['a_id'];
            $auctionType = $arr['auc_type'];
            $eventType = (int)$arr['event_type'];

            $dateHelper = $this->getDateHelper();

            $tzLocation = $arr['timezone_location'] ?: 'UTC';
            if (!empty($this->tz)) {
                $tzLocation = $this->tz;
            }

            if (!$this->isUpdateList) {
                $data['sb'] = (int)$arr['seconds_before'];
                $data['sl'] = (int)$arr['seconds_left'];
            } else {
                $data['status'] = (int)$arr['status'];
                $data['auc_type'] = $auctionType;

                if ($auctionStatusPureChecker->isTimed($auctionType)) {
                    $data['event_type'] = $eventType;
                }

                $data['auction_start_date'] = '';
                $data['end_date'] = '';
                if (
                    $auctionStatusPureChecker->isLiveOrHybrid($auctionType)
                    || $auctionStatusPureChecker->isTimedScheduled($auctionType, $eventType)
                ) {
                    $data['auction_start_date'] = $dateHelper->formatUtcDateIso($arr['auction_start_date'], $this->systemAccountId, $tzLocation);
                }
                if ($auctionStatusPureChecker->isTimedScheduled($auctionType, $eventType)) {
                    $data['end_date'] = $dateHelper->formatUtcDateIso($arr['end_date'], $this->systemAccountId, $tzLocation);
                }
            }

            if ($this->isStartEnding) {
                $data['start_ending_date'] = $dateHelper->formatUtcDateIso($arr['start_closing_date'], $this->systemAccountId, $tzLocation);
            }

            if ($this->isEndDate) {
                $data['end_date'] = $dateHelper->formatUtcDateIso($arr['end_date'], $this->systemAccountId, $arr['timezone_location']);
            }

            $data['is_bidding_console_access_date_utc_met'] = $auctionStatusPureChecker->isLiveOrHybrid($auctionType)
                && $this->isBiddingConsoleAccessDateUtcMet($arr['bidding_console_access_date']);

            $data = $this->onPostIteration($arr, $data);
            $this->auctionData[$auctionId] = $data;
        }

        if ($this->isProfiling()) {
            error_log('create array: ' . ((microtime(true) - $tmpTS) * 1000) . 'ms');
        }

        // free the results and close the db
        $tmpTS = microtime(true);
        $dbResult->free();
        if ($this->isProfiling()) {
            error_log('free results: ' . ((microtime(true) - $tmpTS) * 1000) . 'ms');
        }

        $tmpTS = microtime(true);
        $this->db->close();
        if ($this->isProfiling()) {
            error_log('close db: ' . ((microtime(true) - $tmpTS) * 1000) . 'ms');
        }
        // return the results
        $this->render();
    }

    /**
     * @param string|null $biddingConsoleAccessDateIso -The db field "auction.bidding_console_access_date" is nullable true
     * @return bool
     */
    protected function isBiddingConsoleAccessDateUtcMet(?string $biddingConsoleAccessDateIso): bool
    {
        try {
            $biddingConsoleAccessDate = $biddingConsoleAccessDateIso ? new DateTime($biddingConsoleAccessDateIso, new DateTimeZone('UTC')) : null;
            if (!$biddingConsoleAccessDate) {
                return false;
            }

            $dateUtc = new DateTime('now', new DateTimeZone('UTC'));
            return $dateUtc > $biddingConsoleAccessDate;
        } catch (Exception $e) {
            error_log("Error Bidding Console Access UTC date parsing " . $e->getMessage());
        }
        return false;
    }

    /**
     * @return string
     */
    protected function getAuctionsQuery(): string
    {
        // escape parameters passed in url query
        $auctionIdList = '';
        foreach ($this->auctionIds as $val) {
            if ($auctionIdList !== '') {
                $auctionIdList .= ',';
            }
            $auctionIdList .= $this->db->escape($val);
        }

        $currentDateIso = gmdate(Constants\Date::ISO);
        $currentDateIsoEscaped = $this->db->escape($currentDateIso);

        $statusSelect = '';
        $timed = Constants\Auction::TIMED;
        if ($this->isUpdateList) {
            $ongoing = Constants\Auction::ET_ONGOING;
            $active = Constants\Auction::AS_ACTIVE;
            $closed = Constants\Auction::AS_CLOSED;
            $stateInProgress = Constants\Auction::STATUS_IN_PROGRESS;
            $stateUpcoming = Constants\Auction::STATUS_UPCOMING;
            $stateClosed = Constants\Auction::STATUS_CLOSED;
            $statusSelect = <<<SQL
    ,
    IF(a.auction_type = '{$timed}',
        IF(event_type = {$ongoing},
            {$stateInProgress},
            IF(a.start_bidding_date >= {$currentDateIsoEscaped},
                {$stateUpcoming},
                IF(a.end_date > {$currentDateIsoEscaped}, {$stateInProgress}, {$stateClosed})
            )
        ),
        IF(a.auction_status_id = '{$active}',
            {$stateUpcoming},
            IF(a.auction_status_id = '{$closed}', {$stateClosed}, {$stateInProgress})
        )
    ) AS status
SQL;
        }

        $query = <<<SQL
SELECT 
    UNIX_TIMESTAMP(
      IF(a.auction_type = '{$timed}', a.start_bidding_date, a.start_closing_date)
    )
      - UNIX_TIMESTAMP({$currentDateIsoEscaped}) AS seconds_before,
    UNIX_TIMESTAMP(a.end_date)
      - UNIX_TIMESTAMP({$currentDateIsoEscaped}) AS seconds_left,
    a.id AS a_id,
    a.auction_type AS auc_type,
    a.event_type AS event_type,
    a.auction_status_id AS auc_status,
    a.account_id AS account_id,
    a.start_closing_date,
    a.bidding_console_access_date,
    a.end_date,
    IF(a.auction_type = '{$timed}', a.start_bidding_date, a.start_closing_date) as auction_start_date,
    atz.location AS timezone_location
    {$statusSelect}
FROM auction AS a
LEFT JOIN timezone AS atz ON atz.id = a.timezone_id
WHERE
    a.id IN ({$auctionIdList})
SQL;

        if ($this->isProfiling()) {
            error_log($query);
        }

        return $query;
    }

    /**
     * Set auc ids
     * @param array $auctionIds
     * @return static
     */
    public function setAuctionIds(array $auctionIds): static
    {
        $this->auctionIds = ArrayCast::makeIntArray($auctionIds);
        return $this;
    }

    /**
     * Set system account id
     * @param int|null $systemAccountId -intentionally except null value. For null cases we take account id from main account
     * @return static
     */
    public function setSystemAccountId(?int $systemAccountId): static
    {
        $this->systemAccountId = $systemAccountId;
        return $this;
    }

    /**
     * For checking start ending
     * @param bool $isStartEnding
     * @return static
     */
    public function enableStartEndingCheck(bool $isStartEnding): static
    {
        $this->isStartEnding = $isStartEnding;
        return $this;
    }

    /**
     * For checking end date
     * @param bool $isEndDate
     * @return static
     */
    public function enableEndDateCheck(bool $isEndDate): static
    {
        $this->isEndDate = $isEndDate;
        return $this;
    }

    /**
     * Setting time zone
     * @param string $timeZone
     * @return static
     */
    public function setTz(string $timeZone): static
    {
        $this->tz = trim($timeZone);
        return $this;
    }

    /**
     * Checking list updated or not
     * @param bool $isUpdateList
     * @return static
     */
    public function enableUpdateList(bool $isUpdateList): static
    {
        $this->isUpdateList = $isUpdateList;
        return $this;
    }
}
