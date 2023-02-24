<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\MarkCleared\Single\Update;

/**
 * Trait SingleSettlementCheckClearedMarkerCreateTrait
 * @package Sam\Settlement\Check
 */
trait SingleSettlementCheckClearedMarkerCreateTrait
{
    protected ?SingleSettlementCheckClearedMarker $singleSettlementCheckClearedMarker = null;

    /**
     * @return SingleSettlementCheckClearedMarker
     */
    protected function createSingleSettlementCheckClearedMarker(): SingleSettlementCheckClearedMarker
    {
        return $this->singleSettlementCheckClearedMarker ?: SingleSettlementCheckClearedMarker::new();
    }

    /**
     * @param SingleSettlementCheckClearedMarker $singleSettlementCheckClearedMarker
     * @return $this
     * @internal
     */
    public function setSingleSettlementCheckClearedMarker(SingleSettlementCheckClearedMarker $singleSettlementCheckClearedMarker): static
    {
        $this->singleSettlementCheckClearedMarker = $singleSettlementCheckClearedMarker;
        return $this;
    }
}
