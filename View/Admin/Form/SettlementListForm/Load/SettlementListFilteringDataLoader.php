<?php
/**
 * Settlement List Filtering Data Loader
 * SAM-6279: Refactor Settlement List page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 28, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementListForm\Load;

use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use QMySqli5DatabaseResult;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class SettlementListFilteringDataLoader
 * @package Sam\View\Admin\Form\SettlementListForm\Load
 */
class SettlementListFilteringDataLoader extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * @param int $accountId
     * @param int|null $auctionId null means that there is no filtering by auction id
     * @param bool $isReadOnlyDb
     * @return SettlementListFilteringDto[] - return values for Consignor Users
     */
    public function loadConsignorUserRows(int $accountId, ?int $auctionId = null, bool $isReadOnlyDb = false): array
    {
        $this->enableReadOnlyDb($isReadOnlyDb);
        $n = "\n";
        $mainAccountId = $this->cfg()->get('core->portal->mainAccountId');
        // @formatter:off
        $query =
            "SELECT u.id, u.username " . $n .
            "FROM auction_lot_item AS ali " . $n .
            "LEFT JOIN lot_item AS li " . $n .
            "ON ali.lot_item_id = li.id " . $n .
            "LEFT JOIN user AS u " . $n .
            "ON li.consignor_id = u.id AND u.user_status_id = " . Constants\User::US_ACTIVE . " " . $n .
            "LEFT JOIN consignor AS c " . $n .
            "ON u.id = c.user_id " . $n .
            "WHERE li.active = true " . $n .
            "AND li.account_id = " . $this->escape($accountId) . " " . $n .
            "AND ali.account_id = " . $this->escape($accountId) . " " . $n .
            "AND u.account_id IN (" . $this->escape($accountId) . ", " . $mainAccountId . ") " . $n .
            (($auctionId !== null) ? "AND ali.auction_id = " . $this->escape($auctionId) . " " . $n : '') .
            "AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") " . $n .
            "AND u.id IS NOT NULL " . $n .
            "GROUP BY u.id " . $n .
            "ORDER BY u.username";
        // @formatter:on

        $dbResult = $this->query($query);
        $dtos = [];

        while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $dtos[] = SettlementListFilteringDto::new()->fromDbRow($row);
        }

        return $dtos;
    }
}
