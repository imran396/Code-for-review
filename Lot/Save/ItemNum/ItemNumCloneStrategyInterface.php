<?php
/**
 * SAM-3578: Buyer select from quantity for timed buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Save\ItemNum;

/**
 * Interface ItemNumCloneStrategyInterface
 * @package Sam\Lot\Save\ItemNum
 */
interface ItemNumCloneStrategyInterface
{
    /**
     * @param int $accountId
     * @param int $sourceItemNum
     * @param string $sourceItemNumExt
     * @return array
     */
    public function detectCloneItemNum(int $accountId, int $sourceItemNum, string $sourceItemNumExt): array;
}
