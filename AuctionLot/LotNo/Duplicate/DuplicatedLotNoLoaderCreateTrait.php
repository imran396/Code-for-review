<?php
/**
 * SAM-6373: Refactor duplicated lot# detection at Auction Lot List page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\LotNo\Duplicate;

/**
 * Trait DuplicatedLotNoLoaderCreateTrait
 * @package Sam\AuctionLot\LotNo\Duplicate
 */
trait DuplicatedLotNoLoaderCreateTrait
{
    /**
     * @var DuplicatedLotNoLoader|null
     */
    protected ?DuplicatedLotNoLoader $duplicatedLotNoLoader = null;

    /**
     * @return DuplicatedLotNoLoader
     */
    protected function createDuplicatedLotNoLoader(): DuplicatedLotNoLoader
    {
        return $this->duplicatedLotNoLoader ?: DuplicatedLotNoLoader::new();
    }

    /**
     * @param DuplicatedLotNoLoader $duplicatedLotNoLoader
     * @return $this
     * @internal
     */
    public function setDuplicatedLotNoLoader(DuplicatedLotNoLoader $duplicatedLotNoLoader): static
    {
        $this->duplicatedLotNoLoader = $duplicatedLotNoLoader;
        return $this;
    }
}
