<?php
/**
 * SAM-5651: Refactor Lot No auto filling service
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\LotNo\Calculate;

/**
 * Trait LotNoAdviserCreateTrait
 * @package Sam\AuctionLot\LotNo\Calculate
 */
trait LotNoAdviserCreateTrait
{
    /**
     * @var LotNoAdviser|null
     */
    protected ?LotNoAdviser $lotNoAdviser = null;

    /**
     * @return LotNoAdviser
     */
    protected function createLotNoAdviser(): LotNoAdviser
    {
        return $this->lotNoAdviser ?: LotNoAdviser::new();
    }

    /**
     * @param LotNoAdviser $lotNoAdviser
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotNoAdviser(LotNoAdviser $lotNoAdviser): static
    {
        $this->lotNoAdviser = $lotNoAdviser;
        return $this;
    }
}
