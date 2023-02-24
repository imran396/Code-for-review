<?php
/**
 *
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

namespace Sam\AuctionLot\LotNo\EmptyNo;

/**
 * Trait EmptyLotNoLoaderCreateTrait
 * @package Sam\AuctionLot\LotNo\EmptyNo
 */
trait EmptyLotNoLoaderCreateTrait
{
    /**
     * @var EmptyLotNoLoader|null
     */
    protected ?EmptyLotNoLoader $emptyLotNoLoader = null;

    /**
     * @return EmptyLotNoLoader
     */
    protected function createEmptyLotNoLoader(): EmptyLotNoLoader
    {
        return $this->emptyLotNoLoader ?: EmptyLotNoLoader::new();
    }

    /**
     * @param EmptyLotNoLoader $emptyLotNoLoader
     * @return $this
     * @internal
     */
    public function setEmptyLotNoLoader(EmptyLotNoLoader $emptyLotNoLoader): static
    {
        $this->emptyLotNoLoader = $emptyLotNoLoader;
        return $this;
    }
}
