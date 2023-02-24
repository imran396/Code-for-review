<?php
/**
 * Db data manager for auction lot item
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           May 10, 2015
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Storage;

use InvalidArgumentException;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataManager
 * @package Sam\AuctionLot\Storage
 */
class DataManager extends CustomizableClass
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
     * Lock auction lot items inside transaction
     * @param int[] $auctionLotIds integer or array of auction_lot_item.id
     */
    public function lockInTransactionByIds(array $auctionLotIds): void
    {
        if (empty($auctionLotIds)) {
            throw new InvalidArgumentException("Can't get lock for transaction the auctionLotIds variable is empty");
        }

        foreach ($auctionLotIds as $i => $auctionLotId) {
            $auctionLotIds[$i] = $this->escape($auctionLotId);
        }

        $auctionLotIdList = implode(',', $auctionLotIds);
        $query = <<<SQL
SELECT modified_on FROM auction_lot_item
WHERE id IN ({$auctionLotIdList})
FOR UPDATE
SQL;
        $this->nonQuery($query);
    }
}
