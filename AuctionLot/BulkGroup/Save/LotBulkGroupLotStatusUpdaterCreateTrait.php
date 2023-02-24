<?php
/**
 * SAM-5636: Refactoring of auction_closer.php - move piecemeal lot updating logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 06, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\BulkGroup\Save;

/**
 * Trait BulkGroupLotStatusUpdaterCreateTrait
 * @package
 */
trait LotBulkGroupLotStatusUpdaterCreateTrait
{
    /**
     * @var LotBulkGroupLotStatusUpdater|null
     */
    protected ?LotBulkGroupLotStatusUpdater $lotBulkGroupLotStatusUpdater = null;

    /**
     * @return LotBulkGroupLotStatusUpdater
     */
    protected function createLotBulkGroupLotStatusUpdater(): LotBulkGroupLotStatusUpdater
    {
        return $this->lotBulkGroupLotStatusUpdater ?: LotBulkGroupLotStatusUpdater::new();
    }

    /**
     * @param LotBulkGroupLotStatusUpdater $lotBulkGroupLotStatusUpdater
     * @return static
     * @internal
     */
    public function setLotBulkGroupLotStatusUpdater(LotBulkGroupLotStatusUpdater $lotBulkGroupLotStatusUpdater): static
    {
        $this->lotBulkGroupLotStatusUpdater = $lotBulkGroupLotStatusUpdater;
        return $this;
    }
}
