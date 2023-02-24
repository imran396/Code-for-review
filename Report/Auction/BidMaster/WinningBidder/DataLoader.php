<?php
/**
 * Data loading for AuctionBidReporter
 *
 * SAM-4617: Refactor Auction Bids report
 *
 * @author        Igors Kotlevskis
 * @since         Dec 12, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Report\Auction\BidMaster\WinningBidder;

use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataLoader
 * @package Sam\Report\Auction\AuctionBid
 */
class DataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use FilterAuctionAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function load(): array
    {
        $query = $this->buildResultQuery();
        $this->query($query);
        $rows = $this->fetchAllAssoc();
        return $rows;
    }

    /**
     * @return string
     */
    protected function buildResultQuery(): string
    {
        $n = "\n";
        // @formatter:off
        $query =
            "SELECT"
                . " ab.bidder_num AS bidder_num,"
                . " ui.first_name AS first_name,"
                . " ui.last_name AS last_name,"
                . " u.email AS email,"
                . " ub.company_name AS company_name,"
                . " ub.address AS bill_address,"
                . " ub.address2 AS bill_address2,"
                . " ub.address3 AS bill_address3,"
                . " ub.city AS bill_city,"
                . " ub.state AS bill_state,"
                . " ub.zip AS bill_zip,"
                . " ub.country AS bill_country,"
                . " ui.phone AS phone,"
                . " ui.phone_type AS phone_type,"
                . " ub.fax AS bill_fax"
            . " FROM "
                 . " auction_bidder AS ab"
            . " INNER JOIN user AS u"
                . " ON ab.user_id = u.id"
            . " INNER JOIN user_info AS ui"
                . " ON ab.user_id = ui.user_id"
            . " INNER JOIN user_billing AS ub"
                . " ON ab.user_id = ub.user_id"
            . " WHERE ab.user_id > 0 "
        ;
        // @formatter:on
        $auctionId = $this->getFilterAuctionId();
        if ($auctionId) {
            $query .= " AND ab.auction_id = " . $this->escape($auctionId) . $n;
        }

        $query .= " ORDER BY ab.bidder_num DESC";
        return $query;
    }
}
