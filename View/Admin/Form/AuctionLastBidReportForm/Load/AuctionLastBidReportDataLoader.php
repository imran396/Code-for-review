<?php
/**
 * Auction Last Bid Report Data Loader
 *
 * SAM-5598: Refactor data loader for Auction Last Bid Report at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 25, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLastBidReportForm\Load;

use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionLastBidReportDataLoader
 */
class AuctionLastBidReportDataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use FilterAuctionAwareTrait;
    use LimitInfoAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int - return value of Last Bids Count
     */
    public function count(): int
    {
        $select = 'SELECT COUNT(1) AS total';
        $countQuery = $select
            . $this->buildFromClause()
            . $this->buildWhereClause();
        $this->query($countQuery);
        $rows = $this->fetchAssoc();
        return (int)$rows['total'];
    }

    /**
     * @return AuctionLastBidReportDto[] - return values for Last Bids
     */
    public function load(): array
    {
        $filterAuction = $this->getFilterAuction();
        $select =
            'SELECT'
            . ' ali.lot_item_id,'
            . ' ali.auction_id,'
            . ' ali.lot_num_prefix,'
            . ' ali.lot_num,'
            . ' ali.lot_num_ext,'
            . ' li.name,'
            . ' aub.bidder_num,'
            . ' ui.user_id,'
            . ' ui.first_name,'
            . ' ui.last_name,'
            . ' setsystz.location';
        if ($filterAuction->isTimed()) {
            $select .= ','
                . ' bt.bid AS bid,'
                . ' bt.max_Bid AS max_bid,'
                . ' bt.modified_on AS created_on';
        } else { // $filterAuction->isLiveOrHybrid()
            $select .= ','
                . ' ab.max_bid AS max_bid,'
                . ' ab.modified_on AS created_on';
        }
        $resultQuery = $select
            . $this->buildFromClause()
            . $this->buildWhereClause()
            . $this->getOrderClause()
            . $this->getLimitClause();

        $this->query($resultQuery);
        $rows = $this->fetchAllAssoc();
        $dtos = [];
        foreach ($rows as $row) {
            $dtos[] = AuctionLastBidReportDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @return string
     */
    protected function buildFromClause(): string
    {
        $filterAuction = $this->getFilterAuction();
        if ($filterAuction->isTimed()) {
            $from =
                ' FROM bid_transaction bt'
                . ' INNER JOIN auction_lot_item ali ON ali.lot_item_id = bt.lot_item_id AND ali.auction_id = '
                . $this->escape($this->getFilterAuctionId())
                . ' AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ')'
                . ' INNER JOIN lot_item li ON li.id = bt.lot_item_id'
                . ' INNER JOIN `user` u ON u.id = bt.user_id'
                . ' INNER JOIN user_info ui ON ui.user_id = bt.user_id'
                . ' INNER JOIN auction_bidder aub ON aub.user_id = bt.user_id AND aub.auction_id = '
                . $this->escape($this->getFilterAuctionId())
                . ' INNER JOIN setting_system setsys ON setsys.account_id = '
                . $this->escape($filterAuction->AccountId)
                . ' INNER JOIN timezone setsystz ON setsystz.id = setsys.timezone_id';
        } else {
            $from =
                ' FROM absentee_bid ab'
                . ' INNER JOIN auction_lot_item ali ON ali.lot_item_id = ab.lot_item_id AND ali.auction_id = '
                . $this->escape($this->getFilterAuctionId())
                . ' AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ')'
                . ' INNER JOIN lot_item li ON li.id = ab.lot_item_id'
                . ' INNER JOIN `user` u ON u.id = ab.user_id'
                . ' INNER JOIN user_info ui ON ui.user_id = ab.user_id'
                . ' INNER JOIN auction_bidder aub ON aub.user_id = ab.user_id AND aub.auction_id = '
                . $this->escape($this->getFilterAuctionId())
                . ' INNER JOIN setting_system setsys ON setsys.account_id = '
                . $this->escape($filterAuction->AccountId)
                . ' INNER JOIN timezone setsystz ON setsystz.id = setsys.timezone_id';
        }

        return $from;
    }

    /**
     * @return string
     */
    protected function buildWhereClause(): string
    {
        $filterAuction = $this->getFilterAuction();
        if ($filterAuction->isTimed()) {
            $where = ' WHERE bt.auction_id = ' . $this->escape($filterAuction->Id)
                . ' AND !bt.deleted'
                . ' AND !bt.failed';
        } else {
            $where = ' WHERE ab.auction_id = ' . $this->escape($filterAuction->Id);
        }

        return $where;
    }

    /**
     * @return string
     */
    protected function getOrderClause(): string
    {
        $filterAuction = $this->getFilterAuction();
        if ($filterAuction->isTimed()) {
            $order = ' ORDER BY bt.modified_on DESC';
        } else {
            $order = ' ORDER BY ab.modified_on DESC';
        }
        return $order;
    }

    /**
     * @return string
     */
    protected function getLimitClause(): string
    {
        $limit = $this->getLimit();
        if ($limit === null) {
            return '';
        }
        $query = $limit;

        $offset = $this->getOffset();
        if ($offset) {
            $query = "{$offset}, {$limit}";
        }
        return sprintf(' LIMIT %s', $query);
    }
}
