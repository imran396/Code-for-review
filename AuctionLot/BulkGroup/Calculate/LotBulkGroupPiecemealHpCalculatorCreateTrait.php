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

namespace Sam\AuctionLot\BulkGroup\Calculate;

/**
 * Trait LotBulkGroupPiecemealHpCalculatorCreateTrait
 * @package Sam\AuctionLot\BulkGroup\Calculate
 */
trait LotBulkGroupPiecemealHpCalculatorCreateTrait
{
    /**
     * @var LotBulkGroupPiecemealHpCalculator|null
     */
    protected ?LotBulkGroupPiecemealHpCalculator $lotBulkGroupPiecemealHpCalculator = null;

    /**
     * @return LotBulkGroupPiecemealHpCalculator
     */
    protected function createLotBulkGroupPiecemealHpCalculator(): LotBulkGroupPiecemealHpCalculator
    {
        return $this->lotBulkGroupPiecemealHpCalculator ?: LotBulkGroupPiecemealHpCalculator::new();
    }

    /**
     * @param LotBulkGroupPiecemealHpCalculator $lotBulkGroupPiecemealHpCalculator
     * @return $this
     * @internal
     */
    public function setLotBulkGroupPiecemealHpCalculator(LotBulkGroupPiecemealHpCalculator $lotBulkGroupPiecemealHpCalculator): static
    {
        $this->lotBulkGroupPiecemealHpCalculator = $lotBulkGroupPiecemealHpCalculator;
        return $this;
    }
}
