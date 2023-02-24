<?php
/**
 * SAM-6374: Move auction lot locking in transaction to separate class
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Lock;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;

/**
 * Class AuctionLotLocker
 */
class AuctionLotLocker extends CustomizableClass
{
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * Lock timed online item entry inside transaction
     * @param int $lotItemId
     * @param int $auctionId
     */
    public function lockInTransaction(int $lotItemId, int $auctionId): void
    {
        $db = $this->getDb();
        $availableLotStatusList = implode(',', Constants\Lot::$availableLotStatuses);
        $query = <<<SQL
SELECT modified_on FROM auction_lot_item
WHERE auction_id = {$this->escape($auctionId)}
    AND lot_item_id = {$this->escape($lotItemId)}
    AND lot_status_id IN ({$availableLotStatusList})
FOR UPDATE;
SQL;
        $db->NonQuery($query);
    }
}
