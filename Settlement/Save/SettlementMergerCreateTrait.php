<?php
/**
 *
 * SAM-4613: Settlements merging class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-11-25
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Save;

/**
 * Trait SettlementMergerAwareTrait
 * @package Sam\Settlement\Save
 */
trait SettlementMergerCreateTrait
{
    protected ?SettlementMerger $settlementMerger = null;

    /**
     * @return SettlementMerger
     */
    protected function createSettlementMerger(): SettlementMerger
    {
        return $this->settlementMerger ?: SettlementMerger::new();
    }

    /**
     * @param SettlementMerger $settlementMerger
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setSettlementMerger(SettlementMerger $settlementMerger): static
    {
        $this->settlementMerger = $settlementMerger;
        return $this;
    }
}
