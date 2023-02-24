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

namespace Sam\AuctionLot\LotNo\Fill;

/**
 * Trait LotNoAutoFillerAwareTrait
 * @package Sam\AuctionLot\LotNo\Fill
 */
trait LotNoAutoFillerAwareTrait
{
    /**
     * @var LotNoAutoFiller|null
     */
    protected ?LotNoAutoFiller $lotNoAutoFiller = null;

    /**
     * @return LotNoAutoFiller
     */
    protected function getLotNoAutoFiller(): LotNoAutoFiller
    {
        if ($this->lotNoAutoFiller === null) {
            $this->lotNoAutoFiller = LotNoAutoFiller::new();
        }
        return $this->lotNoAutoFiller;
    }

    /**
     * @param LotNoAutoFiller $lotNoAutoFiller
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotNoAutoFiller(LotNoAutoFiller $lotNoAutoFiller): static
    {
        $this->lotNoAutoFiller = $lotNoAutoFiller;
        return $this;
    }
}
