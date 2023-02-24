<?php
/**
 * Db data manager for auction cache
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

namespace Sam\Auction\Cache\Storage;

use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataManager
 * @package Sam\Auction\Cache\Storage
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
     * Lock auction cache entry inside transaction
     * @param int $auctionId
     */
    public function lockInTransaction(int $auctionId): void
    {
        $query = <<<SQL
SELECT modified_on FROM auction_cache
WHERE auction_id = "{$this->escape($auctionId)}" FOR UPDATE  
SQL;
        $this->nonQuery($query);
    }
}
