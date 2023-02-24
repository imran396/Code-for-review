<?php
/**
 * SAM-4185: Sale No Adviser class
 *
 * @author        Oleg Kovalov
 * Filename       Adviser.php
 * @version       SAM 2.0
 * @since         Apr 12, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.apin
 */

namespace Sam\Auction\SaleNo;

use QMySqli5DatabaseResult;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;

/**
 * Class Adviser
 * @package Sam\Auction\SaleNo
 */
class Adviser extends CustomizableClass
{
    use AuctionReadRepositoryCreateTrait;
    use ConfigRepositoryAwareTrait;
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
     * @param int $accountId
     * @return int|null
     */
    public function suggest(int $accountId): ?int
    {
        $row = $this->createAuctionReadRepository()
            ->filterAccountId($accountId)
            ->filterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->select(
                [
                    'MAX(sale_num) AS `max_sale_num`',
                ]
            )
            ->loadRow();
        $saleNo = $row['max_sale_num'] ?? 0;
        $saleNo++;

        if ($saleNo > $this->cfg()->get('core->db->mysqlMaxInt')) {
            $auctionStatusList = implode(',', Constants\Auction::$notDeletedAuctionStatuses);
            $query = "SELECT IFNULL(a.sale_num, 0)+1 AS next_sale_num " .
                "FROM auction a " .
                "LEFT JOIN auction a2 ON a2.account_id=a.account_id " .
                "AND a2.sale_num=IFNULL(a.sale_num, 0)+1 " .
                "AND a2.auction_status_id IN (" . $auctionStatusList . ") " .
                "WHERE a.account_id=" . $this->escape($accountId) . " " .
                "AND a.auction_status_id IN (" . $auctionStatusList . ") " .
                "AND a2.id IS NULL " .
                "GROUP BY a.sale_num " .
                "LIMIT 1";
            $dbResult = $this->query($query);
            $maxSaleNoRow = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
            $saleNo = $maxSaleNoRow['next_sale_num'];

            if ($saleNo > $this->cfg()->get('core->db->mysqlMaxInt')) {
                return null;
            }
        }
        return $saleNo;
    }
}
