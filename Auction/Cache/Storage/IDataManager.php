<?php
/**
 * Data manager interface for auction cache
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

use Sam\Core\Service\CustomizableClassInterface;

/**
 * Interface IDataManager
 * @package Sam\Auction\Cache\Storage
 */
interface IDataManager extends CustomizableClassInterface
{
    /**
     * Lock auction cache entry inside transaction
     * @param int $auctionId
     */
    public function lockInTransaction(int $auctionId): void;

}
