<?php
/**
 * SAM-4557: Settlement management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/12/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Load;

use LotItem;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementBillableLotLoader
 * @package Sam\Settlement\Load
 */
class SettlementBillableLotLoader extends CustomizableClass
{
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
     * Fetch all items that are not settled yet
     *
     * @param int $accountId
     * @param int|null $auctionId optional auction.id, null means that there is no filtering by li.auction_id and ali.auction_id
     * @param int|null $consignorUserId optional user.id of consignor user, null means that there is no filtering by li.consignor_id
     * @param bool $isReadOnlyDb
     * @return LotItem[]
     */
    public function load(int $accountId, ?int $auctionId = null, ?int $consignorUserId = null, bool $isReadOnlyDb = false): array
    {
        $availableLotStatusList = implode(',', Constants\Lot::$availableLotStatuses);
        $availableSettlementStatusList = implode(',', Constants\Settlement::$availableSettlementStatuses);
        $asDeleted = Constants\Auction::AS_DELETED;
        $usActive = Constants\User::US_ACTIVE;
        $auctionSoldCond = $auctionId ? "AND li.auction_id = {$this->escape($auctionId)}" : '';
        $auctionAssignCond = $auctionId ? "AND ali.auction_id = {$this->escape($auctionId)}" : '';
        $consignorCond = $consignorUserId ? "AND li.consignor_id = {$this->escape($consignorUserId)}" : '';
        $query = <<<SQL
SELECT
    li.*,
    li.consignor_id AS __consignor_id,
    a.sale_num AS __sale_num,
    a.sale_num_ext AS __sale_ext,
    ali.`order` AS __auction_lot_order,
    li.item_num AS __item_num,
    li.item_num_ext AS __item_num_ext
FROM lot_item AS li
LEFT OUTER JOIN auction AS a ON a.id = li.auction_id
LEFT JOIN auction_lot_item ali
    ON ali.lot_item_id=li.id
    AND ali.auction_id=li.auction_id
    AND ali.lot_status_id IN ({$availableLotStatusList})
LEFT JOIN `user` AS u ON u.id = li.consignor_id
WHERE
    li.consignor_id IS NOT NULL
    AND li.hammer_price IS NOT NULL
    AND li.winning_bidder_id IS NOT NULL
    AND li.active = true
    AND IF(li.auction_id > 0, IF(a.auction_status_id != {$asDeleted}, true, false), true)
    AND li.account_id = {$this->escape($accountId)}
    {$auctionSoldCond}
    {$consignorCond}
    AND (SELECT count(1) FROM settlement_item AS si
        INNER JOIN settlement AS s ON si.settlement_id = s.id
        WHERE
            si.lot_item_id = li.id 
            AND si.active = true
            AND s.settlement_status_id IN ({$availableSettlementStatusList})
            AND si.hammer_price >= 0
    ) = 0
    AND u.user_status_id = {$usActive}
SQL;
        //Unsold lots
        $query .= <<<SQL

UNION

SELECT
    li.*,
    li.consignor_id AS __consignor_id,
    a.sale_num AS __sale_num,
    a.sale_num_ext AS __sale_ext,
    ali.`order` AS __auction_lot_order,
    li.item_num AS __item_num,
    li.item_num_ext AS __item_num_ext
FROM lot_item AS li
LEFT JOIN auction_lot_item ali
    ON ali.lot_item_id=li.id
    AND ali.lot_status_id IN ({$availableLotStatusList})
LEFT OUTER JOIN auction AS a ON a.id = ali.auction_id
LEFT JOIN `user` AS u ON u.id = li.consignor_id
LEFT JOIN setting_settlement setstlm ON setstlm.account_id = li.account_id
LEFT JOIN user_consignor_commission_fee uccf ON uccf.user_id = u.id AND uccf.account_id = li.account_id
LEFT JOIN consignor_commission_fee AS unsoldccf_by_li ON li.consignor_unsold_fee_id = unsoldccf_by_li.id AND unsoldccf_by_li.active
LEFT JOIN consignor_commission_fee AS unsoldccf_by_ali ON ali.consignor_unsold_fee_id = unsoldccf_by_ali.id AND unsoldccf_by_ali.active
LEFT JOIN consignor_commission_fee AS unsoldccf_by_a ON a.consignor_unsold_fee_id = unsoldccf_by_a.id AND unsoldccf_by_a.active
LEFT JOIN consignor_commission_fee AS unsoldccf_by_uccf ON uccf.unsold_fee_id = unsoldccf_by_uccf.id AND unsoldccf_by_uccf.active
LEFT JOIN consignor_commission_fee AS unsoldccf_by_setstlm ON setstlm.consignor_unsold_fee_id = unsoldccf_by_setstlm.id AND unsoldccf_by_setstlm.active
WHERE
    li.consignor_id IS NOT NULL
    AND (li.hammer_price >= 0 OR li.hammer_price IS NULL)
    AND li.winning_bidder_id IS NULL
    AND a.auction_status_id != {$asDeleted}
    AND li.active = true
    AND li.account_id = {$this->escape($accountId)}
    {$auctionAssignCond}
    {$consignorCond}
    AND (unsoldccf_by_li.id OR unsoldccf_by_ali.id OR unsoldccf_by_a.id OR unsoldccf_by_uccf.id OR unsoldccf_by_setstlm.id)
    AND (SELECT COUNT(1) FROM settlement_item AS si
        INNER JOIN settlement AS s
            ON si.settlement_id = s.id
        WHERE
            si.lot_item_id = li.id
            AND si.active = TRUE
            AND s.settlement_status_id IN ({$availableSettlementStatusList})
            AND (si.hammer_price > 0 OR si.hammer_price = 0 OR si.hammer_price IS NULL)
    ) = 0
    AND u.user_status_id = {$usActive}
GROUP BY li.id    
ORDER BY __consignor_id, __sale_num, __sale_ext, __auction_lot_order, __item_num, __item_num_ext
SQL;


        $this->enableReadOnlyDb($isReadOnlyDb);
        $dbResult = $this->query($query);

        return LotItem::InstantiateDbResult($dbResult);
    }
}
