<?php
/**
 * SAM-5753: Refactor "Bid Book" report
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\BidBook\Html\Internal\Load;

use Auction;
use QMySqli5DatabaseResult;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;

/**
 * Class AuctionBidBookReportDataLoader
 * @package Sam\Report\Auction\BidBook\Html\Internal\Load
 */
class AuctionBidBookReportDataLoader extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use CurrencyLoaderAwareTrait;
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadAuction(int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb);
    }

    public function loadAuctionCurrencySign(int $auctionId): string
    {
        return $this->getCurrencyLoader()->detectDefaultSign($auctionId);
    }

    /**
     * @param int $startLotOrder
     * @param int $endLotOrder
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return LotData[]
     */
    public function loadLots(int $startLotOrder, int $endLotOrder, int $auctionId, bool $isReadOnlyDb = false): array
    {
        $availableLotStatuses = implode(',', Constants\Lot::$availableLotStatuses);
        $sql = <<<SQL
SELECT  
    ali.lot_num_prefix AS lot_num_prefix,  
    ali.lot_num AS lot_num,  
    ali.lot_num_ext AS lot_num_ext,  
    ali.auction_id AS aid,  
    li.id AS lot_id,  
    li.name AS lot_name,  
    li.reserve_price AS reserve_price,  
    li.low_estimate AS low_estimate,  
    li.high_estimate AS high_estimate,  
    li.starting_bid AS starting_bid,  
    li.hammer_price AS hammer_price,  
    li.auction_id AS auction_id_sold,  
    li.description AS lot_description, 
    ui.company_name AS ccompany_name,  
    ui.first_name AS cfirst_name,  
    ui.last_name AS clast_name,  
    ui.phone AS cphone,  
    li.cost AS cost,
    (SELECT img.id FROM lot_image img WHERE img.lot_item_id = li.id ORDER BY img.order, img.id LIMIT 1) as image_id
FROM  
    auction_lot_item AS ali  
    INNER JOIN lot_item AS li ON li.id = ali.lot_item_id AND li.active = true  
    LEFT JOIN user_info AS ui ON ui.user_id = li.consignor_id  
WHERE  
    ali.lot_status_id IN($availableLotStatuses)
    AND ali.auction_id = {$auctionId}
    AND ali.`order` >= {$startLotOrder}
    AND ali.`order` <= {$endLotOrder}
ORDER BY ali.`order`;
SQL;
        $dbResult = $this->query($sql, $isReadOnlyDb);
        $result = [];
        while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $result[] = LotData::new()->fromDbRow($row);
        }
        return $result;
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return BidData[]
     */
    public function loadHighBids(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): array
    {
        $sql = <<<SQL
SELECT
    ab.max_bid AS max_bid,
    ui.first_name AS bfirst_name,
    ui.last_name AS blast_name,
    acb.bidder_num
FROM
    absentee_bid AS ab
    LEFT JOIN user_info AS ui ON ui.user_id = ab.user_id
    LEFT JOIN auction_bidder AS acb ON acb.auction_id = ab.auction_id AND acb.user_id = ab.user_id
WHERE
    lot_item_id = {$lotItemId}
    AND ab.auction_id = {$auctionId}
    AND ab.max_bid > 0
ORDER BY
    ab.max_bid DESC, placed_on LIMIT 2
SQL;

        $dbResult = $this->query($sql, $isReadOnlyDb);
        $result = [];
        while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $result[] = BidData::new()->fromDbRow($row);
        }
        return $result;
    }
}
