<?php
/**
 * Data loader interface
 *
 * SAM-6388: Active countdown on admin - auction - lots
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/21/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown\Internal\Load;

use Sam\Core\Service\CustomizableClassInterface;

interface DataLoaderInterface extends CustomizableClassInterface
{

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function load(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): ?int;
}

