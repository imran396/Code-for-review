<?php
/**
 * SAM-6377: Separate bulk group related logic to classes
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 13, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\BulkGroup\Save\Date;

/**
 * Trait LotBulkGroupLotDateUpdaterCreateTrait
 * @package Sam\AuctionLot\BulkGroup
 */
trait LotBulkGroupLotDateUpdaterCreateTrait
{
    /**
     * @var LotBulkGroupLotDateUpdater|null
     */
    protected ?LotBulkGroupLotDateUpdater $lotBulkGroupLotDateUpdater = null;

    /**
     * @return LotBulkGroupLotDateUpdater
     */
    protected function createLotBulkGroupLotDateUpdater(): LotBulkGroupLotDateUpdater
    {
        return $this->lotBulkGroupLotDateUpdater ?: LotBulkGroupLotDateUpdater::new();
    }

    /**
     * @param LotBulkGroupLotDateUpdater $lotBulkGroupLotDateUpdater
     * @return $this
     * @internal
     */
    public function setLotBulkGroupLotDateUpdater(LotBulkGroupLotDateUpdater $lotBulkGroupLotDateUpdater): static
    {
        $this->lotBulkGroupLotDateUpdater = $lotBulkGroupLotDateUpdater;
        return $this;
    }
}
