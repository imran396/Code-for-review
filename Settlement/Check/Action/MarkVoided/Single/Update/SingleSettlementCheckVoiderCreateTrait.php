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

namespace Sam\Settlement\Check\Action\MarkVoided\Single\Update;

/**
 * Trait SingleSettlementCheckVoiderCreateTrait
 * @package Sam\Settlement\Check
 */
trait SingleSettlementCheckVoiderCreateTrait
{
    protected ?SingleSettlementCheckVoider $singleSettlementCheckVoider = null;

    /**
     * @return SingleSettlementCheckVoider
     */
    protected function createSingleSettlementCheckVoider(): SingleSettlementCheckVoider
    {
        return $this->singleSettlementCheckVoider ?: SingleSettlementCheckVoider::new();
    }

    /**
     * @param SingleSettlementCheckVoider $singleSettlementCheckVoider
     * @return $this
     * @internal
     */
    public function setSingleSettlementCheckVoider(SingleSettlementCheckVoider $singleSettlementCheckVoider): static
    {
        $this->singleSettlementCheckVoider = $singleSettlementCheckVoider;
        return $this;
    }
}
