<?php

/**
 * It is a auction closer class for closing.
 *
 * Related tickets:
 *
 * SAM-3224 : Refactoring of auction_closer.php
 *
 * @author        Imran Rahman
 * Filename       Closer.php
 * @version       SAM 2.0
 * @since         May 14, 2016
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 *
 */

namespace Sam\Auction\Close;

use Auction;
use DateTime;
use Exception;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;

/**
 * Class Closer
 * @package Sam\Auction\Close
 */
class AuctionCloser extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use AuctionWriteRepositoryAwareTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * closing auctions without active items that ended in the past
     *
     * @param int $editorUserId
     * @return void
     */
    public function closeTimedAuctions(int $editorUserId): void
    {
        try {
            // Fetch ready for closing live auctions
            $timed = Constants\Auction::TIMED;
            $etScheduled = Constants\Auction::ET_SCHEDULED;
            $lsActive = Constants\Lot::LS_ACTIVE;
            $openAuctionStatusList = implode(',', Constants\Auction::$openAuctionStatuses);
            $query = <<<SQL
SELECT a.* 
FROM auction a 
WHERE a.auction_type = '{$timed}'
    AND (a.event_type = {$etScheduled}
         OR a.event_type IS NULL)
    AND a.auction_status_id IN ({$openAuctionStatusList})
    AND a.end_date < {$this->escape($this->getCurrentDateUtcIso())}
    AND (SELECT COUNT(1)
        FROM auction_lot_item
        WHERE auction_lot_item.auction_id=a.id
            AND auction_lot_item.lot_status_id = '{$lsActive}') <= 0
    AND (SELECT COUNT(1)
        FROM account
        WHERE account.id = a.account_id
            AND account.active) > 0
SQL;
            $dbResult = $this->query($query);
            $auctions = Auction::InstantiateDbResult($dbResult);
            // Log prepared for closing timed auctions
            $auctionIds = [];
            foreach ($auctions as $auction) {
                $auctionIds[] = $auction->Id;
            }
            log_info(
                'Timed auctions ready for closing'
                . composeSuffix(['count' => count($auctionIds), 'a' => $auctionIds])
            );
            // Close timed auctions
            $closedAuctionIds = [];
            foreach ($auctions as $auction) {
                try {
                    $endDate = $this->findLastClosedLotEndDate($auction->Id);
                    $auction->toClosed();
                    $auction->EndDate = $endDate ?? $this->getCurrentDateUtc();
                    $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);
                    $closedAuctionIds[] = $auction->Id;
                } catch (Exception $e) {
                    log_warning(
                        'Failed closing auction'
                        . composeSuffix(['a' => $auction->Id, 'error' => $e->getMessage()])
                    );
                }
            }
            log_info(
                'Timed auctions successfully closed'
                . composeSuffix(['count' => count($closedAuctionIds), 'a' => $closedAuctionIds])
            );
        } catch (Exception $e) {
            log_error('Failed closing auctions without active items that ended in the past: ' . $e->getMessage());
        }
    }

    /**
     * Get lot with max end date of an auction
     *
     * @param int $auctionId auction id
     * @return DateTime|null
     */
    public function findLastClosedLotEndDate(int $auctionId): ?DateTime
    {
        $row = $this->createAuctionLotItemReadRepository()
            ->select(['ali.end_date'])
            ->joinLotItemFilterActive(true)
            ->filterAuctionId($auctionId)
            ->filterLotStatusId([Constants\Lot::LS_SOLD, Constants\Lot::LS_UNSOLD,])
            ->orderByEndDate(false)
            ->loadRow();
        $endDateIso = $row['end_date'] ?? null;
        if ($endDateIso) {
            return new DateTime($endDateIso);
        }
        return null;
    }
}
