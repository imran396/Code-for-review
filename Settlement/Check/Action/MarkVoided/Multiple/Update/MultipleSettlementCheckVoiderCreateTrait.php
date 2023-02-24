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

namespace Sam\Settlement\Check\Action\MarkVoided\Multiple\Update;

/**
 * Trait MultipleSettlementCheckVoiderCreateTrait
 * @package Sam\Settlement\Check
 */
trait MultipleSettlementCheckVoiderCreateTrait
{
    protected ?MultipleSettlementCheckVoider $multipleSettlementCheckVoider = null;

    /**
     * @return MultipleSettlementCheckVoider
     */
    protected function createMultipleSettlementCheckVoider(): MultipleSettlementCheckVoider
    {
        return $this->multipleSettlementCheckVoider ?: MultipleSettlementCheckVoider::new();
    }

    /**
     * @param MultipleSettlementCheckVoider $multipleSettlementCheckVoider
     * @return $this
     * @internal
     */
    public function setMultipleSettlementCheckVoider(MultipleSettlementCheckVoider $multipleSettlementCheckVoider): static
    {
        $this->multipleSettlementCheckVoider = $multipleSettlementCheckVoider;
        return $this;
    }
}
