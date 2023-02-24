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

namespace Sam\Settlement\Check\Action\MarkPosted\Single\Validate;

/**
 * Trait SingleSettlementCheckPostedMarkingValidatorCreateTrait
 * @package Sam\Settlement\Check
 */
trait SingleSettlementCheckPostedMarkingValidatorCreateTrait
{
    protected ?SingleSettlementCheckPostedMarkingValidator $singleSettlementCheckPostedMarkingValidator = null;

    /**
     * @return SingleSettlementCheckPostedMarkingValidator
     */
    protected function createSingleSettlementCheckPostedMarkingValidator(): SingleSettlementCheckPostedMarkingValidator
    {
        return $this->singleSettlementCheckPostedMarkingValidator ?: SingleSettlementCheckPostedMarkingValidator::new();
    }

    /**
     * @param SingleSettlementCheckPostedMarkingValidator $singleSettlementCheckPostedMarkingValidator
     * @return $this
     * @internal
     */
    public function setSingleSettlementCheckPostedMarkingValidator(SingleSettlementCheckPostedMarkingValidator $singleSettlementCheckPostedMarkingValidator): static
    {
        $this->singleSettlementCheckPostedMarkingValidator = $singleSettlementCheckPostedMarkingValidator;
        return $this;
    }
}
