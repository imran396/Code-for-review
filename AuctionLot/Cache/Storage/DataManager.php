<?php
/**
 * Db data manager for auction lot item cache
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Apr 04, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Cache\Storage;

use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataManager
 * @package Sam\AuctionLot\Cache\Storage
 */
class DataManager extends CustomizableClass implements IDataManager
{
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return static or customized class extended from it
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Lock auction lot item cache entry inside transaction
     * @param int $auctionLotId
     */
    public function lockInTransaction(int $auctionLotId): void
    {
        $query = <<<SQL
SELECT current_bid FROM auction_lot_item_cache
WHERE auction_lot_item_id = "{$this->escape($auctionLotId)}"
FOR UPDATE  
SQL;
        $this->nonQuery($query);
    }
}
