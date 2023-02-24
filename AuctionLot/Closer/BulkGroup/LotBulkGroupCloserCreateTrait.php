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

namespace Sam\AuctionLot\Closer\BulkGroup;

/**
 * Trait LotBulkGroupCloserCreateTrait
 * @package Sam\AuctionLot\Closer\BulkGroup
 */
trait LotBulkGroupCloserCreateTrait
{
    /**
     * @var LotBulkGroupCloser|null
     */
    protected ?LotBulkGroupCloser $lotBulkGroupCloser = null;

    /**
     * @return LotBulkGroupCloser
     */
    protected function createLotBulkGroupCloser(): LotBulkGroupCloser
    {
        return $this->lotBulkGroupCloser ?: LotBulkGroupCloser::new();
    }

    /**
     * @param LotBulkGroupCloser $lotBulkGroupCloser
     * @return $this
     * @internal
     */
    public function setLotBulkGroupCloser(LotBulkGroupCloser $lotBulkGroupCloser): static
    {
        $this->lotBulkGroupCloser = $lotBulkGroupCloser;
        return $this;
    }
}
