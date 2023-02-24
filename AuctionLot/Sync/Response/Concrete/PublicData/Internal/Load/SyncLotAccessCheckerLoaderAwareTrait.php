<?php
/**
 * SAM-6503: Even when the Limit Bidding Info Permission is set to admin it shows asking bid for others User as well
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal\Load;


/**
 * Trait SyncLotAccessCheckerLoaderAwareTrait
 * @package Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal\Load
 * @internal
 */
trait SyncLotAccessCheckerLoaderAwareTrait
{
    protected ?SyncLotAccessCheckerLoader $syncLotAccessCheckerLoader = null;

    /**
     * @return SyncLotAccessCheckerLoader
     */
    protected function getSyncLotAccessCheckerLoader(): SyncLotAccessCheckerLoader
    {
        if ($this->syncLotAccessCheckerLoader === null) {
            $this->syncLotAccessCheckerLoader = SyncLotAccessCheckerLoader::new();
        }
        return $this->syncLotAccessCheckerLoader;
    }

    /**
     * @param SyncLotAccessCheckerLoader $syncLotAccessCheckerLoader
     * @return static
     * @internal
     */
    public function setSyncLotAccessCheckerLoader(SyncLotAccessCheckerLoader $syncLotAccessCheckerLoader): static
    {
        $this->syncLotAccessCheckerLoader = $syncLotAccessCheckerLoader;
        return $this;
    }
}
