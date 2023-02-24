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

namespace Sam\Settlement\Check\Action\MarkPosted\Multiple\Update;

/**
 * Trait MultipleSettlementCheckPostedMarkerCreateTrait
 * @package Sam\Settlement\Check
 */
trait MultipleSettlementCheckPostedMarkerCreateTrait
{
    protected ?MultipleSettlementCheckPostedMarker $multipleSettlementCheckPostedMarker = null;

    /**
     * @return MultipleSettlementCheckPostedMarker
     */
    protected function createMultipleSettlementCheckPostedMarker(): MultipleSettlementCheckPostedMarker
    {
        return $this->multipleSettlementCheckPostedMarker ?: MultipleSettlementCheckPostedMarker::new();
    }

    /**
     * @param MultipleSettlementCheckPostedMarker $multipleSettlementCheckPostedMarker
     * @return $this
     * @internal
     */
    public function setMultipleSettlementCheckPostedMarker(MultipleSettlementCheckPostedMarker $multipleSettlementCheckPostedMarker): static
    {
        $this->multipleSettlementCheckPostedMarker = $multipleSettlementCheckPostedMarker;
        return $this;
    }
}
