<?php
/**
 *
 * SAM-4761: Lot from settlement removing service
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-13
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Delete;

/**
 * Trait LotFromSettlementRemoverAwareTrait
 * @package Sam\Settlement\Delete
 */
trait LotFromSettlementRemoverAwareTrait
{
    protected ?LotFromSettlementRemover $lotFromSettlementRemover = null;

    /**
     * @return LotFromSettlementRemover
     */
    protected function getLotFromSettlementRemover(): LotFromSettlementRemover
    {
        if ($this->lotFromSettlementRemover === null) {
            $this->lotFromSettlementRemover = LotFromSettlementRemover::new();
        }
        return $this->lotFromSettlementRemover;
    }

    /**
     * @param LotFromSettlementRemover $lotFromSettlementRemover
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotFromSettlementRemover(LotFromSettlementRemover $lotFromSettlementRemover): static
    {
        $this->lotFromSettlementRemover = $lotFromSettlementRemover;
        return $this;
    }
}
