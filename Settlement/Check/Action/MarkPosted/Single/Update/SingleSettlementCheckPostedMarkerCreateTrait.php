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

namespace Sam\Settlement\Check\Action\MarkPosted\Single\Update;

/**
 * Trait SingleSettlementCheckPostedMarkerCreateTrait
 * @package Sam\Settlement\Check
 */
trait SingleSettlementCheckPostedMarkerCreateTrait
{
    protected ?SingleSettlementCheckPostedMarker $singleSettlementCheckPostedMarker = null;

    /**
     * @return SingleSettlementCheckPostedMarker
     */
    protected function createSingleSettlementCheckPostedMarker(): SingleSettlementCheckPostedMarker
    {
        return $this->singleSettlementCheckPostedMarker ?: SingleSettlementCheckPostedMarker::new();
    }

    /**
     * @param SingleSettlementCheckPostedMarker $singleSettlementCheckPostedMarker
     * @return $this
     * @internal
     */
    public function setSingleSettlementCheckPostedMarker(SingleSettlementCheckPostedMarker $singleSettlementCheckPostedMarker): static
    {
        $this->singleSettlementCheckPostedMarker = $singleSettlementCheckPostedMarker;
        return $this;
    }
}
