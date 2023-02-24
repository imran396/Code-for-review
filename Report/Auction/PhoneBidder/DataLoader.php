<?php
/**
 * Data loading for AuctionPhoneBidderReporter
 *
 * SAM-4617: Refactor Auction Bids report
 *
 * @author        Vahagn Hovsepyan
 * @since         Dec 13, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Report\Auction\PhoneBidder;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class DataLoader
 */
class DataLoader extends CustomizableClass
{
    use FilterAwareTrait;
    use DbConnectionTrait;
    use FilterAuctionAwareTrait;
    use LimitInfoAwareTrait;
    use SystemAccountAwareTrait;

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
        $resultQuery = $this->buildResultQuery();
        $this->query($resultQuery);
        $rows = [];
        while ($row = $this->fetchAssoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        $params = $this->getParamsForQuery();
        $queryParts = QueryBuilder::new()->getQueryParts($params);
        $from = $queryParts['from'];
        $countSelect = $queryParts['select_count'];
        $countConditions = $queryParts['where_count'];
        $countQuery = $countSelect . $from . $countConditions;
        $this->query($countQuery);
        $row = $this->fetchAssoc();
        $count = Cast::toInt($row['lot_total']);
        return $count;
    }

    /**
     * @return string
     */
    public function buildResultQuery(): string
    {
        $params = $this->getParamsForQuery();
        $queryParts = QueryBuilder::new()->getQueryParts($params);
        $select = $queryParts['select'];
        $from = $queryParts['from'];
        $where = $queryParts['where'];
        $order = $queryParts['order'];
        $limit = $queryParts['limit'];
        $resultQuery = $select . $from . $where . $order . $limit;
        return $resultQuery;
    }

    /**
     * @return array
     */
    protected function getParamsForQuery(): array
    {
        $params = [];
        $params['AuctionId'] = $this->getFilterAuctionId();
        $params['BidderId'] = $this->getBidderId();
        $params['Clerk'] = $this->getClerk();
        $params['MinLot'] = $this->getMinLotNum();
        $params['MaxLot'] = $this->getMaxLotNum();
        $params['UnassignedOnly'] = $this->isUnassignedOnly();
        $params['AllLots'] = $this->isAllLots();
        $params['Limit'] = $this->getLimit();
        $params['Offset'] = $this->getOffset();
        return $params;
    }
}
