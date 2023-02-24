<?php
/**
 * SAM-8005: Allow decimals in quantity
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Quantity\Scale;

use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotQuantityScaleLoader
 * @package Sam\AuctionLot\Quantity
 */
class LotQuantityScaleLoader extends CustomizableClass
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

    public function loadAuctionLotQuantityScale(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): int
    {
        $query = <<<SQL
SELECT COALESCE(
        ali.quantity_digits, 
        li.quantity_digits, 
        (SELECT lc.quantity_digits
         FROM lot_category lc
           INNER JOIN lot_item_category lic ON lc.id = lic.lot_category_id
         WHERE lic.lot_item_id = li.id
           AND lc.active = 1
         ORDER BY lic.id
         LIMIT 1), 
        (SELECT seta.quantity_digits FROM setting_auction seta WHERE seta.account_id = li.account_id)
    ) as quantity_scale
FROM auction_lot_item ali
         INNER JOIN lot_item li ON li.id = ali.lot_item_id
WHERE ali.auction_id = {$auctionId}
  AND ali.lot_item_id = {$lotItemId}
LIMIT 1
SQL;
        $this->query($query, $isReadOnlyDb);
        $row = $this->fetchAssoc();
        if (!$row) {
            return 0;
        }
        return (int)$row['quantity_scale'];
    }

    public function loadLotItemQuantityScale(int $lotItemId, bool $isReadOnlyDb = false): int
    {
        $query = <<<SQL
SELECT COALESCE(
        li.quantity_digits, 
        (SELECT lc.quantity_digits
         FROM lot_category lc
           INNER JOIN lot_item_category lic ON lc.id = lic.lot_category_id
         WHERE lic.lot_item_id = li.id
           AND lc.active = 1
         ORDER BY lic.id
         LIMIT 1), 
        (SELECT seta.quantity_digits FROM setting_auction seta WHERE seta.account_id = li.account_id)
    ) as quantity_scale
FROM lot_item li
WHERE li.id = {$lotItemId}
SQL;
        $this->query($query, $isReadOnlyDb);
        $row = $this->fetchAssoc();
        if (!$row) {
            return 0;
        }
        return (int)$row['quantity_scale'];
    }
}
