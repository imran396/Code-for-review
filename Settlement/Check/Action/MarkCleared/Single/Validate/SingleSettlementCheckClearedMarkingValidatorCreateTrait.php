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

namespace Sam\Settlement\Check\Action\MarkCleared\Single\Validate;

/**
 * Trait SingleSettlementCheckClearedMarkingValidatorCreateTrait
 * @package Sam\Settlement\Check
 */
trait SingleSettlementCheckClearedMarkingValidatorCreateTrait
{
    protected ?SingleSettlementCheckClearedMarkingValidator $singleSettlementCheckClearedMarkingValidator = null;

    /**
     * @return SingleSettlementCheckClearedMarkingValidator
     */
    protected function createSingleSettlementCheckClearedMarkingValidator(): SingleSettlementCheckClearedMarkingValidator
    {
        return $this->singleSettlementCheckClearedMarkingValidator ?: SingleSettlementCheckClearedMarkingValidator::new();
    }

    /**
     * @param SingleSettlementCheckClearedMarkingValidator $singleSettlementCheckClearedMarkingValidator
     * @return $this
     * @internal
     */
    public function setSingleSettlementCheckClearedMarkingValidator(SingleSettlementCheckClearedMarkingValidator $singleSettlementCheckClearedMarkingValidator): static
    {
        $this->singleSettlementCheckClearedMarkingValidator = $singleSettlementCheckClearedMarkingValidator;
        return $this;
    }
}
