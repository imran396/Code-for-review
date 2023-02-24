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

namespace Sam\AuctionLot\BulkGroup\Delete;

/**
 * Trait LotBulkGroupRevokerCreateTrait
 * @package Sam\AuctionLot\BulkGroup
 */
trait LotBulkGroupRevokerCreateTrait
{
    /**
     * @var LotBulkGroupRevoker|null
     */
    protected ?LotBulkGroupRevoker $lotBulkGroupRevoker = null;

    /**
     * @return LotBulkGroupRevoker
     */
    protected function createLotBulkGroupRevoker(): LotBulkGroupRevoker
    {
        return $this->lotBulkGroupRevoker ?: LotBulkGroupRevoker::new();
    }

    /**
     * @param LotBulkGroupRevoker $lotBulkGroupRevoker
     * @return $this
     * @internal
     */
    public function setLotBulkGroupRevoker(LotBulkGroupRevoker $lotBulkGroupRevoker): static
    {
        $this->lotBulkGroupRevoker = $lotBulkGroupRevoker;
        return $this;
    }
}
