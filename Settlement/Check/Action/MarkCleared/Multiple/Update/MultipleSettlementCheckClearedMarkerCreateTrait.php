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

namespace Sam\Settlement\Check\Action\MarkCleared\Multiple\Update;

/**
 * Trait MultipleSettlementCheckClearedMarkerCreateTrait
 * @package Sam\Settlement\Check
 */
trait MultipleSettlementCheckClearedMarkerCreateTrait
{
    protected ?MultipleSettlementCheckClearedMarker $multipleSettlementCheckClearedMarker = null;

    /**
     * @return MultipleSettlementCheckClearedMarker
     */
    protected function createMultipleSettlementCheckClearedMarker(): MultipleSettlementCheckClearedMarker
    {
        return $this->multipleSettlementCheckClearedMarker ?: MultipleSettlementCheckClearedMarker::new();
    }

    /**
     * @param MultipleSettlementCheckClearedMarker $multipleSettlementCheckClearedMarker
     * @return $this
     * @internal
     */
    public function setMultipleSettlementCheckClearedMarker(MultipleSettlementCheckClearedMarker $multipleSettlementCheckClearedMarker): static
    {
        $this->multipleSettlementCheckClearedMarker = $multipleSettlementCheckClearedMarker;
        return $this;
    }
}
