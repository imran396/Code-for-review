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

namespace Sam\Settlement\Check\Action\MarkVoided\Single\Validate;

/**
 * Trait SingleSettlementCheckVoidingValidatorCreateTrait
 * @package Sam\Settlement\Check
 */
trait SingleSettlementCheckVoidingValidatorCreateTrait
{
    protected ?SingleSettlementCheckVoidingValidator $singleSettlementCheckVoidingValidator = null;

    /**
     * @return SingleSettlementCheckVoidingValidator
     */
    protected function createSingleSettlementCheckVoidingValidator(): SingleSettlementCheckVoidingValidator
    {
        return $this->singleSettlementCheckVoidingValidator ?: SingleSettlementCheckVoidingValidator::new();
    }

    /**
     * @param SingleSettlementCheckVoidingValidator $singleSettlementCheckVoidingValidator
     * @return $this
     * @internal
     */
    public function setSingleSettlementCheckVoidingValidator(SingleSettlementCheckVoidingValidator $singleSettlementCheckVoidingValidator): static
    {
        $this->singleSettlementCheckVoidingValidator = $singleSettlementCheckVoidingValidator;
        return $this;
    }
}
