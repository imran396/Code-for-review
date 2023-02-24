<?php
/**
 * SAM-6019: Auction end date overhaul
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\AuctionDynamic\Storage;

use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Db data manager for auction dynamic
 *
 * Class DataManager
 * @package Sam\Auction\Storage
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
     * Lock auction dynamic entry inside transaction
     * @param int $auctionId
     */
    public function lockInTransaction(int $auctionId): void
    {
        $query = <<<SQL
SELECT extend_all_start_closing_date FROM auction_dynamic
WHERE auction_id = "{$this->escape($auctionId)}"
FOR UPDATE  
SQL;
        $this->nonQuery($query);
    }
}

